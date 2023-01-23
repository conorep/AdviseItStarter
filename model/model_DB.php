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
            $selectAllIDs = "SELECT schedule_id FROM schedules";
            return mysqli_query($this->getConn(), $selectAllIDs);
        }

        /**
         * This function takes a new ID as a parameter and references it against existing schedule IDs in the database.
         * @param $newID String new token ID
         * @return bool true if ID parameter is not found in DB or nothing in DB yet,
         *      false if found in DB
         */
        public function checkTokens(string $newID): bool
        {
            $result = $this->getAllUniqueIDs();

            if ($result && mysqli_num_rows($result) > 0)
            {
                while ($row = mysqli_fetch_assoc($result))
                {
                    if ($row['schedule_id'] == $newID)
                    {
                        return false;
                    }
                }
            } else
            {
                return true;
            }
            return true;
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
         * This function runs a SELECT ALL query on the database 'schedules' table. It returns an array of all rows.
         * @return array array of fetched IDs
         */
        public function getAllScheduleIDs(): array
        {
            $arrayOfIDs = array();
        
            $selectAll = "SELECT schedule_id FROM schedules ORDER BY modified_date";
            
            $sqlSelectAll = $this->getConn()->prepare($selectAll);
            $sqlSelectAll->execute();
            $result = $sqlSelectAll->get_result();
            while($row = $result->fetch_assoc())
            {
                $arrayOfIDs[]=$row['schedule_id'];
            }
            return $arrayOfIDs;
        }

        /**
         * This function creates a new schedule in the database.
         *      It utilizes mysqli's prepare and bind_param functions to handle SQL validation
         *      and avoid SQL injection.
         * @param $idParam String unique token ID
         * @param $fallParam String fall quarter info
         * @param $winterParam String winter quarter info
         * @param $springParam String spring quarter info
         * @param $summerParam String summer quarter info
         * @return bool false if nothing returned from DB query, mysqli_result if data returned
         */
        public function createNewSchedule(string $idParam, string $advisorParam, string $fallParam, string $winterParam,
                                          string $springParam, string $summerParam): bool
        {
            /*create SQL statement and use mysqli's prepare function for safe execution preparation*/
           $newSchedule = "INSERT INTO schedules (schedule_id, advisor_name, fall_qrtr, winter_qrtr, spring_qrtr, summer_qrtr)
                                VALUES (?, ?, ?, ?, ?, ?);";
           $sqlStatement = $this->getConn()->prepare($newSchedule);
           
           /*bind parameters using mysqli and declaring them as String (does not allow for SQL injection)*/
           $sqlStatement->bind_param("ssssss", $id, $advisor, $fall, $winter, $spring, $summer);
           
           /*update all bound parameters*/
           $id = $idParam;
           $advisor = $advisorParam;
           $fall = $fallParam;
           $winter = $winterParam;
           $spring = $springParam;
           $summer = $summerParam;
           
           return $sqlStatement->execute();
        }
    
        /**
         * This function retrieves an existing schedule from the database.
         * @param $scheduleID String unique token ID of a schedule
         * @return array|false|null associative array if row fetched, false if no rows, null if failure
         */
        public function retrieveSchedule(string $scheduleID)
        {
            $retrieveSchedule = "SELECT * FROM schedules WHERE schedule_id = ? LIMIT 1";
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
         * @return bool
         */
        public function updateSchedule(string $scheduleID, string $sqlUpdate, array $valsArr): bool
        {
            /*instantiate empty variables for possible usage, dependent on amount of updated fields sent in*/
            $uniqueID = ''; $one = ''; $two = ''; $three = ''; $four = ''; $five = '';

            /*transfer variables into empty array for each in array of values sent into function, plus uniqueID*/
            /*loop through columnNameVars and save the required amount of reference field variables in columnVars*/
            $columnVars = array();
            $columnNameVars = [$one, $two, $three, $four, $five];

            for($x = 0; $x < count($valsArr); $x++)
            {
                $columnVars[] = $columnNameVars[$x];
            }
            $columnVars[] = $uniqueID;

            /*create a string of 's's for string bind_param function*/
            $stringRefs = str_repeat('s', count($columnVars));

            /*prepare query*/
            $sqlStatement = $this->getConn()->prepare($sqlUpdate);

            /*bind parameters using mysqli and declaring them as string (does not allow for SQL injection)*/
            $sqlStatement->bind_param($stringRefs, ...$columnVars);

            /*save actual values to array of variables*/
            for($x = 0; $x < count($columnVars); $x++)
            {
                if($x < count($columnVars) -1)
                {
                    $columnVars[$x] = $valsArr[$x];
                } else
                {
                    $columnVars[$x] = $scheduleID;
                }
            }

            /*execute*/
            return $sqlStatement->execute();
        }

    }