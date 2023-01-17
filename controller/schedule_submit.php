<?php
    session_start();

    /**
     * This file handles the submitting of new schedules in the background after Ajax
     * has posted the form data to $_SESSION.
     */
    
    require("controller_class.php");
    require("../model/model_DB.php");
    
    /*create objects*/
    $dbFunctions = new ModelDB();
    $modelCalls = new ControllerClass($dbFunctions);
    
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $uniqueID = '';
        $fallInfo = '';
        $winterInfo = '';
        $springInfo = '';
        $summerInfo = '';

        if(!empty($_POST))
        {
            if(isset($_POST['UniqueID']))
            {
                $uniqueID = $_POST['UniqueID'];
            }
            if(isset($_POST['Fall']))
            {
                $fallInfo = $_POST['Fall'];
            }
            if(isset($_POST['Winter']))
            {
                $winterInfo = $_POST['Winter'];
            }
            if(isset($_POST['Spring']))
            {
                $springInfo = $_POST['Spring'];
            }
            if(isset($_POST['Summer']))
            {
                $summerInfo = $_POST['Summer'];
            }

            if($uniqueID)
            {
                $_SESSION['scheduleToken'] = $uniqueID;
                $infoHere = $modelCalls->createNewPlan($uniqueID, $fallInfo, $winterInfo, $springInfo, $summerInfo);
                echo $infoHere;
            }
        }

    }
