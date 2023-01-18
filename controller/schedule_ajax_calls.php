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


    /**
     * The POST ajax call creates a new schedule in the DB using this block of code.
     */
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        /*make arrays of variables and the associated $_POST terms that will be saved to them*/
        $infoArray = ['UniqueID', 'Fall', 'Winter', 'Spring', 'Summer'];
        $varArr = [$uniqueID = '', $fallInfo = '', $winterInfo = '', $springInfo = '', $summerInfo = ''];

        if(!empty($_POST))
        {
            for($x = 0; $x < count($infoArray); $x++)
            {
                if(isset($_POST[$infoArray[$x]]))
                {
                    $varArr[$x] = $_POST[$infoArray[$x]];
                }
            }

            if($varArr[0])
            {
                $_SESSION['scheduleToken'] = $varArr[0];
                $infoHere = $modelCalls->createNewPlan($varArr[0], $varArr[1], $varArr[2], $varArr[3], $varArr[4]);
                echo $infoHere;
            }
            postUnset($infoArray);
        }
    }

    /**
     * The GET ajax call facilitates retrieval of one specific record using this block of code.
     */
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $uniqueID = '';
        if(!empty($_GET))
        {
            var_dump($_GET);
            if(isset($_GET['ScheduleIDGet']))
            {
                $uniqueID = $_GET['ScheduleIDGet'];
            }
            if($uniqueID)
            {
                $_SESSION['scheduleToken'] = $uniqueID;
            }
        }
    }

    /**
     * A simple function to unset the variables used in the $_POST array
     * @param $postInfoArr array the array of values that are used in the 'new schedule' code
     * @return void
     */
    function postUnset(array $postInfoArr)
    {
        foreach($postInfoArr as $postInfo)
        {
            if($postInfo !== 'UniqueID')
            {
                unset($_POST[$postInfo]);
            }
        }
    }
