<?php
    require("../controller/php/model_calls.php");
    require("../model/php/db_functions.php");
    /**
     * This file contains the code for the "new plan" view.
     * It utilizes PHP to dynamically generate HTML output for the planning view.
     * @version 1.0
     * @author Conor O'Brien
     */
    $dbFunctions = new DBFunctions();
    $modelCalls = new ModelCalls($dbFunctions);
    $quartersArr = array("Fall", "Winter", "Spring", "Summer");


    echo "        
        <form id='scheduleSubmit' method='post'>
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
                        <textarea class='quarterInput form-control' id='" . $quarter . "' rows='5'></textarea>
                    </div>
    ";
    }

    echo "
                    <button id='submit-schedule-button' class='scheduleButton'>
                        SUBMIT
                    </button>
    ";

    echo "            </div>
            </div>
        </form>";