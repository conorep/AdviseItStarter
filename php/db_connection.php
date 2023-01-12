<?php

/*THIS FILE WILL BE STORED SECURELY ON THE SERVER*/
require("connection_info.php");

/*create connection*/
$connection = new mysqli($dbhost, $dbuser, $dbpass, $db);

if($connection -> connect_error)
{
    die("Database connection failed: " . $connection->connect_error);
} else
{
    echo " Connection successful!\n";
    $selectOne = "SELECT * FROM schedules";

    $result = mysqli_query($connection, $selectOne);
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

    /*close connection when done (for now)*/
    $connection->close();
}


