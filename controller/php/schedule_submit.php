<?php
    session_start();

    /**
     * This file handles the submitting of new schedules in the background.
     *      Ajax sends the data here as JSON.
     */

    require('model_calls.php');
    require('../../model/php/db_functions.php');
    $dbFunctionsSubmit = new DBFunctions();
    $modelCallsSubmit = new ModelCalls($dbFunctionsSubmit);

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $uniqueID = '';
        $fallInfo = '';
        $winterInfo = '';
        $springInfo = '';
        $summerInfo = '';

        if(!empty($_POST))
        {
            /*TODO: VALIDATE INPUTS!!!!*/
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
                /*TODO: check to see if successful*/

                $infoHere = $modelCallsSubmit->createNewPlan($uniqueID, $fallInfo, $winterInfo, $springInfo, $summerInfo);
                echo "<script>$infoHere</script>";

//            echo "<script>$uniqueID</script>";
            }
        }

    }
