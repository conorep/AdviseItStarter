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
        require("connection_info.php");
        $connection = new mysqli($dbhost, $dbuser, $dbpass, $db);

        if($connection -> connect_error)
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
     * @param $newID
     * @return bool true if ID parameter is not found in DB or nothing in DB yet,
     *      false if found in DB
     */
    public function checkTokens($newID): bool
    {
        $result = DBFunctions::getAllUniqueIDs();
        if(mysqli_num_rows($result) > 0)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                if($row['scheduleID'] == $newID)
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

    /*
     * TODO: think about how to implement this with multiple advisors creating schedules at the same time.
            In particular, think about re-calling this or checkUniqueIDs when submitting.
    */
    /**
     * This function creates a unique 6-digit ID to use as a schedule token.
     *      It checks all existing DB tokens for uniqueness. If unique, it is usable.
     *      If not, it calls itself and tries again.
     * @return void
     */
    public function generateUniqueID()
    {
        $uniqueToken = substr(uniqid(), 7, 13);
        $uniqueToken = strtoupper($uniqueToken);
        if($this->checkTokens($uniqueToken))
        {
            echo "ID IS UNIQUE! TOKEN: " . $uniqueToken;
        } else
        {
            self::generateUniqueID();
        }
    }

    /*TODO: function that adds new schedule to DB*/
    public function createNewSchedule()
    {

    }

    /**
     * This function runs a SELECT ALL query on the database 'schedules' table.
     * @return bool|mysqli_result false if nothing returned from DB query, mysqli_result if data returned
     */
    public function getAllScheduleInfo()
    {
        $selectAll = "SELECT * FROM schedules";
        return mysqli_query($this->conn, $selectAll);

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