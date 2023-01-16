<?php
    session_start();
    /**
     * This file contains object instantiation for easy access to the same existing objects from other files.
     * @version 1.0
     * @author Conor O'Brien
     */

    require("../controller/php/model_calls.php");
    require("../model/php/db_functions.php");

    /*create objects*/
    $dbFunctions = new DBFunctions();
    $modelCalls = new ModelCalls($dbFunctions);

    /*shared array of quarter names*/
    $quartersArr = array("Fall", "Winter", "Spring", "Summer");