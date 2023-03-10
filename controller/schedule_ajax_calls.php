<?php
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    /**
     * This file handles the submitting of new schedules in the background after Ajax
     *  has posted the form data to $_SESSION.
     * @version 1.0
     * @author Conor O'Brien
     */
    include('../include/object_creation.php');

    /**
     * The POST ajax call creates a new schedule in the DB using this block of code.
     */
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        /*using for external API request testing*/
        if(isset($_POST['RETURN_DATA_TEST_8A8A8A8A']))
        {
            echo $_POST['RETURN_DATA_TEST_8A8A8A8A'] . "\n";
            echo json_encode($dbFunctions->getAllSchedules());
        } else
        {
			/*TODO: make this submit once for each of the */
            /*make arrays of variables and the associated $_POST terms that will be saved to them*/
            $infoArray = ['UniqueID', 'AdvisorName', 'PlanYear', 'Fall', 'Winter', 'Spring', 'Summer'];
            $varArr = [$uniqueID='', $advisor='', $year='', $fallInfo='', $winterInfo='', $springInfo='', $summerInfo=''];
    
            if(!empty($_POST))
            {
                for($x = 0; $x < count($infoArray); $x++)
                {
                    if(isset($_POST[$infoArray[$x]]))
                    {
						if(empty($_POST[$infoArray[$x]]))
						{
							$_POST[$infoArray[$x]] = ' ';
						}
                        $varArr[$x] = $_POST[$infoArray[$x]];
                    }
                }
                if($varArr[0])
                {
                    $_SESSION['scheduleToken'] = $varArr[0];
                    $infoHere = $controllerObject->createNewPlan($varArr[0], $varArr[1], (int)$varArr[2], $varArr[3],
						$varArr[4], $varArr[5], $varArr[6]);
                    echo $infoHere;
                }
                $controllerObject->unsetVars($infoArray);
            }
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
            if(isset($_GET['ScheduleIDGet']))
            {
                $uniqueID = $_GET['ScheduleIDGet'];
            }
            if($uniqueID)
            {
                $_SESSION['scheduleToken'] = $uniqueID;
            }
            unset($_GET['ScheduleIDGet']);
        }
    }
