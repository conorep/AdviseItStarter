<?php

    /**
     * This class defines functions for database usage.
     * @version 1.0
     * @author Conor O'Brien
     */
    class DBFunctions
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
            $selectAllIDs = "SELECT scheduleID FROM schedules";
            return mysqli_query(DBFunctions::getConn(), $selectAllIDs);
        }

        /**
         * This function takes a new ID as a parameter and references it against existing schedule IDs in the database.
         * @param $newID String new token ID
         * @return bool true if ID parameter is not found in DB or nothing in DB yet,
         *      false if found in DB
         */
        private function checkTokens($newID): bool
        {
            $result = DBFunctions::getAllUniqueIDs();

            if (mysqli_num_rows($result) > 0)
            {
                while ($row = mysqli_fetch_assoc($result))
                {
                    if ($row['scheduleID'] == $newID)
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

        /*TODO: function that adds new schedule to DB*/
        /**
         * This function creates a new schedule in the database.
         * @param $id String unique token ID
         * @param $fall String fall quarter info
         * @param $winter String winter quarter info
         * @param $spring String spring quarter info
         * @param $summer String summer quarter info
         * @return bool|mysqli_result false if nothing returned from DB query, mysqli_result if data returned
         */
        public function createNewSchedule(string $id, string $fall, string $winter, string $spring, string $summer)
        {
           $newSchedule = "INSERT INTO schedules(scheduleID, fallQrtr, winterQrtr, springQrtr, summerQrtr) VALUES('". $id ."','". $fall ."', '". $winter ."', '". $spring ."', '".$summer ."')";

            return mysqli_query($this->getConn(), $newSchedule);
        }

        public function createNewTest()
        {
            $newSchedule = "INSERT INTO schedules (scheduleID, fallQrtr, winterQrtr, springQrtr, summerQrtr) VALUES('8A8B8C', 'yes', 'yes', 'yes', 'yes')";

            $response = mysqli_query($this->getConn(), $newSchedule);
        }

        /**
         * This function retrieves an existing schedule from the database.
         * @param $scheduleID String unique token ID of a schedule
         * @return void
         */
        public function retrieveSchedule($scheduleID)
        {
            null;
        }

        /**
         * This function runs a SELECT ALL query on the database 'schedules' table.
         * @return bool|mysqli_result false if nothing returned from DB query, mysqli_result if data returned
         */
        public function getAllScheduleInfo()
        {
            $selectAll = "SELECT * FROM schedules";
            return mysqli_query($this->getConn(), $selectAll);

            /*
            if(mysqli_num_rows($result) > 0)
            {

                while($row = mysqli_fetch_assoc($result))
                {
                    echo " Schedule ID: " . $row["scheduleID"];
                }
            } else
            {

                echo " No results from select query.";
            }
            */
        }

    }