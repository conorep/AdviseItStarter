<?php
    session_start();

    /**
     * This file includes some redundant html used in two slightly different ways (for new sand existing schedule).
     * @version 1.0
     * @author Conor O'Brien
     */
    /*this delineates what the submit button will say (submit or update based on view)*/
    $submitOrUpdate = 'SUBMIT';
    /*this delineates what the submit button will be labeled to do (submit new schedule or update existing schedule)*/
    $subOrUpVal = 'newScheduleSubmit';
    /*this is used to save the schedule token in the update view*/
    $idVal = '';
    $databaseValArr = array('Fall'=>'fallQrtr', 'Winter'=>'winterQrtr', 'Spring'=>'springQrtr', 'Summer'=>'summerQrtr');
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
                <div class='col-10 col-md-5 shadow quarter-box m-2 p-2'>
                    <h2>" . $quarter . "</h2>
                    <label for='" . $quarter . "'>CLASSES</label>
                    <textarea class='quarterInput form-control' id='" . $quarter . "' rows='5' 
                                name='" . $quarter . "' >";

        echo $_SESSION['pageID'] == 'RetrieveView' ? $_SESSION['planData'][$rowVal]  :  '';

        echo       "</textarea>
                </div>";
    }
    
    echo "
                    <button type='submit' id='submit-schedule-button' class='scheduleButton' name='".$subOrUpVal. "'
                                value='".$idVal."'>
                        ". $submitOrUpdate ."
                    </button>
                </div>
            </div>
        </form>";