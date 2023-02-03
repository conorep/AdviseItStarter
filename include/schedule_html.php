<?php
    session_start();

    /**
     * This file includes html used in two slightly different ways (for creating new and viewing existing schedules).
     * @version 1.0
     * @author Conor O'Brien
     */
    /*this delineates what the submit button will say (submit or update based on view)*/
    $submitOrUpdate = 'SAVE';
    /*this delineates what the submit button will be labeled to do (submit new schedule or update existing schedule)*/
    $subOrUpVal = 'newScheduleSubmit';

    /*this is used to save the schedule token in the update view*/
    $idVal = '';
    /*this is used with the object_creation $quartersArr to supply the column names needed for data from/to the database*/
    $databaseValArr = array('Fall'=>'fall_qrtr', 'Winter'=>'winter_qrtr', 'Spring'=>'spring_qrtr', 'Summer'=>'summer_qrtr');
    $rowVal ='';
    /*get current year for displaying each quarter's year*/
	date('m/d') < '07/01' ? $rowYear = date('Y', strtotime('-1 year')) : $rowYear = date('Y');

    /*if the retrieved schedule is to be displayed, change submit button to update*/
    if($_SESSION['pageID'] == 'RetrieveView')
    {
        $submitOrUpdate = 'SAVE UPDATE';
        $subOrUpVal = 'scheduleUpdate';
        $rowYear = $_SESSION['planData']['plan_year'];
        if(isset($_SESSION['scheduleToken']) && $_SESSION['scheduleToken'] !== '')
        {
            $idVal = $_SESSION['scheduleToken'];
        }
    }
    echo "
        <div id='year-div-" .$rowYear. "' class='getYear row justify-content-center'>
            <label for='PlanYear' id='year-label'><strong>Plan Year: </strong>
                <input id='plan-year' name='PlanYear' value='" .$rowYear. "' readonly>
            </label>";
    foreach ($quartersArr as $quarter)
    {
        if($quarter == 'Fall')
        {
            $displayYear = $rowYear;
        } else
        {
            $displayYear = $rowYear +1;
        }
        if($_SESSION['pageID'] == 'RetrieveView')
        {
            $rowVal = $databaseValArr[$quarter];
        }
        echo "                    
                <div class='no-print col-10 col-md-5 shadow quarter-box m-2 p-2'>
                    <label for='" .$quarter. "-" .$displayYear. "' class='no-print form-label'><strong>" .$quarter. " " .$displayYear. "</strong>
                         Classes & Comments</label>
                    <textarea class='quarterInput form-control' id='" . $quarter . "-" .$displayYear. "' rows='5'
                                name='" . $quarter . "' >";

        echo $_SESSION['pageID'] == 'RetrieveView' ? $_SESSION['planData'][$rowVal]  :  '';

        echo "</textarea>
                </div>";
        echo $_SESSION['pageID'] == 'RetrieveView' ? "
                <div class='hide-for-print print'>
                        <h2 class='top-margin'><strong>" .$quarter. " " .$displayYear. "</strong> Classes & Comments</h2>
                        <div class='print-schedule'><pre>" . $_SESSION['planData'][$rowVal] . "</pre></div>
                </div>" : '';
    }
    echo "
        </div>";
    $controllerObject->anotherYearButton(1);
    echo "
                    <button type='submit' id='submit-schedule-button' class='scheduleButton' name='".$subOrUpVal. "'
                                value='".$idVal."'>
                        ". $submitOrUpdate ."
                    </button>";
    
    echo $_SESSION['pageID'] == 'RetrieveView' ? "
                    <button type='button' onclick='window.print()' id='print-schedule-button'
                        class='no-print ms-3 scheduleButton'>PRINT</button>" :  '';
    echo"
                </div>
            </div>
        </form>";