<?php
    session_start();
    /**
     * This file contains object instantiation for easy access to the same existing objects from other files.
     * @version 1.0
     * @author Conor O'Brien
     */

    require("../controller/controller_class.php");
    require("../model/model_DB.php");

    /*create objects*/
    $dbFunctions = new ModelDB();
    $controllerObject = new ControllerClass($dbFunctions);

    /*shared array of quarter names*/
    $quartersArr = array("Fall", "Winter", "Spring", "Summer");