<?php
    /**
     * This class defines functions for database usage.
     * @version 1.0
     * @author Conor O'Brien
     */
    class ModelDB
    {
        private $conn;

        /**
         * This is the DBFunctions class constructor.
         * It calls the database connection function and saves the active
         *      connection to the class $conn variable.
         */
        public function __construct()
        {
            $this->conn = $this->connectToDB();
        }

        /**
         * This private function creates a database connection.
         * @return mysqli|void
         */
        private function connectToDB()
        {
            /*TODO: move connection_info.php somewhere secure*/
            require("connection_info.php");
            $connection = new mysqli($dbhost, $dbuser, $dbpass, $db);

            if ($connection->connect_error)
            {
                die("Database connection failed: " . $connection->connect_error);
            } else
            {
                return $connection;
            }
        }

        /**
         * This function returns the database connection.
         * @return mysqli|null returns database connection if it exists, returns null if not.
         */
        public function getConn(): ?mysqli
        {
            return $this->conn;
        }

        /**
         * This function queries the DB for all existing unique schedule IDs.
         * @return bool|mysqli_result false if nothing returned from DB query,
         *      mysqli_result if data returned
         */
        public function getAllUniqueIDs()
        {
            $selectAllIDs = "SELECT schedule_id FROM schedule_ids";
            return mysqli_query($this->getConn(), $selectAllIDs);
        }
		
        /*TODO: update this to NOT use uniqid*/
        /**
         * This function creates a unique 6-digit ID to use as a schedule token.
         *      It checks all existing DB tokens for uniqueness. If unique, it is usable.
         *      If not, it calls itself and tries again.
         * @return string unique ID token
         */
        public function generateUniqueID(): string
        {
            /*utilize PHP's uniqid() function and set all alphabetic characters to uppercase*/
            $uniqueToken = substr(uniqid(), 7, 13);
            $uniqueToken = strtoupper($uniqueToken);

            if ($this->checkTokens($uniqueToken))
            {
                return $uniqueToken;
            } else
            {
                self::generateUniqueID();
            }
            /*this is useless and not reachable - just getting rid of a few bogus IDE flags*/
            return '';
        }
	
		/**
		 * This function takes a new ID as a parameter and references it against existing schedule IDs in the database.
		 * @param $newID String new token ID
		 * @return int|true true if ID is unique (not found), id_num if found
		 */
		public function checkTokens(string $newID)
		{
			$result = $this->getAllUniqueIDs();
		
			if ($result && mysqli_num_rows($result) > 0)
			{
				while ($row = mysqli_fetch_assoc($result))
				{
					if ($row['schedule_id'] == $newID)
					{
						return (int)$row['id_num'];
					}
				}
			} else
			{
				return true;
			}
			return true;
		}
		
		/*TODO: pare down functions in model. Can likely have this functionality in another query function.
		 Ideally, as few queries as possible to get required data (quicker app when done right).*/
		/**
		 * This function takes an id_num and plan_year to check for existence of a plan with a certain year. If it
		 * 		exists already, return true so that an update is triggered. Otherwise, return false.
		 * @param int $planIdNum
		 * @param int $planYear
		 * @return bool true if id_num with plan_year exists, false if not
		 */
		public function checkPlanYear(int $planIdNum, int $planYear): bool
		{
			$sqlStatement = "SELECT 1 FROM schedules WHERE id_num = ? AND plan_year = ?;";
			$sqlStatement = $this->getConn()->prepare($sqlStatement);
			$sqlStatement->bind_param('ii', $idNum, $year);
			$idNum = $planIdNum;
			$year = $planYear;
			$sqlStatement->execute();
			if($sqlStatement->get_result())
			{
				return true;
			}
			return false;
		}
    
        /**
         * This function runs a SELECT ALL query on the database 'schedules' table. It returns an array of all rows.
         * @return array array of fetched IDs
         */
        public function getAllSchedules(): array
        {
            $arrayOfIDs = array();
        
            $selectAll =
                "SELECT schedule_ids.schedule_id, schedules.advisor_name, schedules.created_date, schedules.modified_date
					FROM schedules INNER JOIN schedule_ids ON schedule_ids.id_num = schedules.id_num ORDER BY created_date";
            
            $sqlSelectAll = $this->getConn()->prepare($selectAll);
            $sqlSelectAll->execute();
            $result = $sqlSelectAll->get_result();
            while($row = $result->fetch_assoc())
            {
                $arrayOfIDs[]=['scheduleId'=>$row['schedule_id'], 'advisorName'=>$row['advisor_name'],
                    'createdDate'=>$row['created_date'], 'modifiedDate'=>$row['modified_date']];
            }
            return $arrayOfIDs;
        }
	
		/**
		 * 	This function creates a schedule ID token instance in the schedule_ids table. This is used to get the id_num
		 *        that will be created by the MySQL table, which is in turn used by createNewSchedule in this class.
		 * @param string $idParam the 6-digit token used to create a row in the schedule_ids table
		 * @return bool false if failure, true if success
		 */
		private function createScheduleIDNum(string $idParam): bool
		{
			$newScheduleNum = "INSERT INTO schedule_ids (schedule_id) VALUE (?);";
			
			$sqlStatement = $this->getConn()->prepare($newScheduleNum);
			$sqlStatement->bind_param("s", $idToken);
			$idToken = $idParam;
			return $sqlStatement->execute();
		}
	
		/**
		 * This function retrieves the freshly-created record's id_num value.
		 * @param string $idParam 6-digit schedule token used to retrieve id_num
		 * @return array|false|null associative array if row fetched, false if no rows, null if failure
		 */
		private function getIDNum(string $idParam)
		{
			$sqlStatement = "SELECT * FROM schedule_ids WHERE schedule_id = ? LIMIT 1";
			$sqlStatement = $this->getConn()->prepare($sqlStatement);
			$sqlStatement->bind_param("s", $idToken);
			$idToken = $idParam;
			
			$sqlStatement->execute();
			return $sqlStatement->get_result()->fetch_assoc();
		}

		/*TODO: set this up to use a date that has been sent from elsewhere in order to create multiple rows that can
		 		be tied together by one token ID.*/
        /**
         * This function creates a new schedule in the database.
         *      It utilizes mysqli's prepare and bind_param functions to handle SQL validation
         *      and avoid SQL injection.
         *      It also checks the current date and sets the plan year accordingly.
         * @param $idParam String unique token ID
         * @param $fallParam String fall quarter info
         * @param $winterParam String winter quarter info
         * @param $springParam String spring quarter info
         * @param $summerParam String summer quarter info
		 * @param $planYear int year reflecting the plan
         * @return bool false if nothing returned from DB query, true if record created
         */
        public function createNewSchedule(string $idParam, string $advisorParam, string $fallParam, string $winterParam,
                                          string $springParam, string $summerParam, int $planYear): bool
        {
			$newScheduleIDNum = $this->createScheduleIDNum($idParam);
			if($newScheduleIDNum)
			{
				$getIDNum = $this->getIDNum($idParam);
				if($getIDNum)
				{
					/*create SQL statement and use mysqli's prepare function for safe execution preparation*/
					$newSchedule =
						"INSERT INTO schedules (id_num, advisor_name, fall_qrtr, winter_qrtr, spring_qrtr, summer_qrtr, plan_year)
                                VALUES (?, ?, ?, ?, ?, ?, ?);";
					$sqlStatement = $this->getConn()->prepare($newSchedule);
					
					/*bind parameters using mysqli and declaring them as String (does not allow for SQL injection)*/
					$sqlStatement->bind_param("isssssi", $id, $advisor, $fall, $winter, $spring, $summer, $date);
					
					/*update all bound parameters*/
					$id = $getIDNum['id_num'];
					$advisor = $advisorParam;
					$fall = $fallParam;
					$winter = $winterParam;
					$spring = $springParam;
					$summer = $summerParam;
					$date = $planYear;
					
					return $sqlStatement->execute();
				}
			}
			return false;
        }
    
        /**
         * This function retrieves an existing schedule from the database.
         * @param $scheduleID String unique token ID of a schedule
         * @return array|false|null associative array if row fetched, false if no rows, null if failure
         */
        public function retrieveSchedule(string $scheduleID)
        {
            $retrieveSchedule =
				"SELECT * FROM schedules INNER JOIN schedule_ids ON schedule_ids.id_num = schedules.id_num
         			WHERE schedule_ids.schedule_id = ? LIMIT 1";
            $sqlStatement = $this->getConn()->prepare($retrieveSchedule);
    
            $sqlStatement->bind_param("s", $id);
            $id = $scheduleID;
            
            $sqlStatement->execute();
            return $sqlStatement->get_result()->fetch_assoc();
        }

        /**
         * This function updates a row in the database.
         * @param string $scheduleID ID of row to update
         * @param string $sqlUpdate update query built in controller
         * @param array $valsArr values to update
		 * @param int $planYear row year to update
         * @return bool
         */
        public function updateSchedule(string $scheduleID, string $sqlUpdate, array $valsArr, int $planYear): bool
        {
            $getIDNum = $this->getIDNum($scheduleID);
            /*instantiate empty variables for possible usage, dependent on amount of updated fields sent in*/
            $uniqueID = ''; $one = ''; $two = ''; $three = ''; $four = ''; $five = ''; $plan_year = 0;

            /*transfer variables into empty array for each in array of values sent into function, plus uniqueID*/
            /*loop through columnNameVars and save the required amount of reference field variables in columnVars*/
            $columnVars = array();
            $columnNameVars = [$one, $two, $three, $four, $five];

            for($x = 0; $x < count($valsArr); $x++)
            {
                $columnVars[] = $columnNameVars[$x];
            }
            $columnVars[] = $uniqueID;
			$columnVars[] = $plan_year;

            /*create a string of 's's for string bind_param function and then add the ii for the int id_num and int
            plan_year at the end*/
            $stringRefs = str_repeat('s', count($columnVars) -2);
            $stringRefs .= 'ii';
            /*prepare query*/
            $sqlStatement = $this->getConn()->prepare($sqlUpdate);
            /*bind parameters using mysqli and declaring them as string (does not allow for SQL injection)*/
            $sqlStatement->bind_param($stringRefs, ...$columnVars);

            /*save actual values to array of variables*/
            for($x = 0; $x < count($columnVars); $x++)
            {
				if($x < count($columnVars) -2)
				{
					$columnVars[$x] = $valsArr[$x];
				} else
				{
					$x == count($columnVars) -2 ? $columnVars[$x] = $getIDNum['id_num'] : $columnVars[$x] = $planYear;
				}
            }

            /*execute*/
            return $sqlStatement->execute();
        }

        /**
         * This function retrieves an admin account from the database using a provided email.
         * @param string $tryEmail email provided by user trying to log in
         * @return array|false|null associative array if row fetched, false if no rows, null if failure
         */
        public function getAdminInfo(string $tryEmail)
        {
            $adminEmail = '';

            $getEmail = "SELECT * FROM administrators WHERE email = ?  LIMIT 1";
            $sqlStatement = $this->getConn()->prepare($getEmail);

            $sqlStatement->bind_param("s", $adminEmail);
            $adminEmail = $tryEmail;

            $sqlStatement->execute();
            return $sqlStatement->get_result()->fetch_assoc();
        }
    }