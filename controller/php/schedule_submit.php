<?php
    session_start();

    /**
     * This file handles the submitting of new schedules in the background after Ajax
     * has posted the form data to $_SESSION.
     */
    
    require("model_calls.php");
    require("../../model/php/db_functions.php");
    
    /*create objects*/
    $dbFunctions = new DBFunctions();
    $modelCalls = new ModelCalls($dbFunctions);
    
    
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
                $infoHere = $modelCalls->createNewPlan($uniqueID, $fallInfo, $winterInfo, $springInfo, $summerInfo);
                echo $infoHere;
            }
        }

    }
