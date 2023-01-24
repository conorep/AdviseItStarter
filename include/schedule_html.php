<?php
    session_start();

    /**
     * This file includes html used in two slightly different ways (for creating new and viewing existing schedules).
     * @version 1.0
     * @author Conor O'Brien
     */
    /*this delineates what the submit button will say (submit or update based on view)*/
    $submitOrUpdate = 'SUBMIT';
    /*this delineates what the submit button will be labeled to do (submit new schedule or update existing schedule)*/
    $subOrUpVal = 'newScheduleSubmit';

    /*this is used to save the schedule token in the update view*/
    $idVal = '';
    $databaseValArr = array('Fall'=>'fall_qrtr', 'Winter'=>'winter_qrtr', 'Spring'=>'spring_qrtr', 'Summer'=>'summer_qrtr');
    $rowVal ='';

    if($_SESSION['pageID'] == 'RetrieveView')
    {
        $submitOrUpdate = 'UPDATE';
        $subOrUpVal = 'scheduleUpdate';
        if(isset($_SESSION['scheduleToken']) && $_SESSION['scheduleToken'] !== '')
        {
            $idVal = $_SESSION['scheduleToken'];
        }
    }
    
    foreach ($quartersArr as $quarter)
    {
        if($_SESSION['pageID'] == 'RetrieveView')
        {
            $rowVal = $databaseValArr[$quarter];
        }
        echo "                    
                <div class='no-print col-10 col-md-5 shadow quarter-box m-2 p-2'>
                    <label for='" . $quarter . "' class='no-print form-label'><strong>" . $quarter . "</strong>
                         Classes & Comments</label>
                    <textarea class='quarterInput form-control' id='" . $quarter . "' rows='5'
                                name='" . $quarter . "' >";

        echo $_SESSION['pageID'] == 'RetrieveView' ? $_SESSION['planData'][$rowVal]  :  '';

        echo "
                    </textarea>
                </div>";
        echo $_SESSION['pageID'] == 'RetrieveView' ? "
                <div class='hide-for-print print'>
                        <h2 class='top-margin'><strong>" . $quarter . "</strong> Classes & Comments</h2>
                        <div class='print-schedule'><pre>" . $_SESSION['planData'][$rowVal] . "</pre></div>
                </div>" : '';
    }
    
    echo "
                    <button type='submit' id='submit-schedule-button' class='scheduleButton' name='".$subOrUpVal. "'
                                value='".$idVal."'>
                        ". $submitOrUpdate ."
                    </button>
                </div>
            </div>
        </form>";