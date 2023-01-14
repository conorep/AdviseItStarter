<?php
include("model/php/db_functions.php");

$databaseFunctions = new DBFunctions();
/*TODO: generateUniqueID should be tied to a new plan on-click event*/
$databaseFunctions->generateUniqueID();