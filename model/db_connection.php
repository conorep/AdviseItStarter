<?php
    /*TODO: get rid of this file. was used for testing only.*/
    /*THIS FILE WILL BE STORED SECURELY ON THE SERVER*/
    require("connection_info.php");

    /*create connection*/
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $db);

    if ($connection->connect_error)
    {
        die("Database connection failed: " . $connection->connect_error);
    } else
    {
        echo " Connection successful!\n";

        $selectAll = "SELECT * FROM schedules";
        $result = mysqli_query($connection, $selectAll);
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                echo " Schedule ID: " . $row["scheduleID"];
            }
        } else
        {
            echo " No results from select query.";
        }
        /*this isn't necessary in a program of this size. close does the trick. not stressing about memory leak or anything.*/
        //mysqli_free_result($result);

        /*close connection when done (for now)*/
        $connection->close();
    }

