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
    echo "Connection successful!";
}