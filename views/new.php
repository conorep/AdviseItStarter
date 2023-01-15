<?php
include("../model/php/db_functions.php");
/**
 * This file contains the code for the "new plan" view.
 * It utilizes PHP to dynamically generate HTML output for the planning view.
 * @version 1.0
 * @author Conor O'Brien
 */
$databaseFunctions = new DBFunctions();
/*TODO: generateUniqueID should be tied to a new plan on-click event*/
$databaseFunctions->generateUniqueID();

$quartersArr = array("Fall", "Winter", "Spring", "Summer");

echo "        
        <form id='scheduleSubmit' method='post'>
            <div class='container-fluid'>
                <div class='row justify-content-center'>";

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

echo "            </div>
            </div>
        </form>";