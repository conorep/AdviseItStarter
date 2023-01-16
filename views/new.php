<?php
    session_start();

    /**
     * This file contains the code for the "new plan" view.
     * It utilizes PHP to dynamically generate HTML output for the planning view.
     * @version 1.0
     * @author Conor O'Brien
     */

    require("../controller/php/model_calls.php");
    require("../model/php/db_functions.php");

    /*create objects*/
    $dbFunctions = new DBFunctions();
    $modelCalls = new ModelCalls($dbFunctions);
//    $_SESSION['modelCalls'] = serialize($modelCalls);

    /*make the objects usable in schedule_submit.php*/
//    include("../controller/php/schedule_submit.php");

    $quartersArr = array("Fall", "Winter", "Spring", "Summer");

    echo "        
        <form id='scheduleSubmit' action='' method='post'>
            <div class='container-fluid'>
                <div class='row justify-content-center'>";

    /*call the unique token generator method*/
    $modelCalls->displayUniqueToken();

    foreach ($quartersArr as $quarter)
    {
        echo "                    
                    <div class='col-10 col-md-5 shadow quarter-box m-2 p-2'>
                        <h2>" . $quarter . "</h2>
                        <label for='" . $quarter . "'>CLASSES</label>
                        <textarea class='quarterInput form-control' id='" . $quarter . "' rows='5' name='" . $quarter . "'></textarea>
                    </div>
    ";
    }

    echo "
                    <button type='submit' id='submit-schedule-button' class='scheduleButton' value='newSchedule' name='newScheduleSubmit'>
                        SUBMIT
                    </button>
    ";

    echo "            </div>
            </div>
        </form>";