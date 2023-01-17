<?php
    session_start();

    /**
     * This file includes some redundant html used in two slightly different ways (for new sand existing schedule).
     * @version 1.0
     * @author Conor O'Brien
     */
    $readOnlyOrNot = '';
    $databaseValArr = array('Fall'=>'fallQrtr', 'Winter'=>'winterQrtr', 'Spring'=>'springQrtr', 'Summer'=>'summerQrtr');
    $rowVal ='';

    if($_SESSION['pageID'] == 'RetrieveView')
    {
        $readOnlyOrNot = 'readonly';
    }

    /*if viewing a freshly-created schedule, readonly is toggled 'on' for the textareas*/
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
                                name='" . $quarter . "' ".$readOnlyOrNot.">";

        echo $_SESSION['pageID'] == 'RetrieveView' ? $_SESSION['planData'][$rowVal]  :  '';

        echo       "</textarea>
                </div>";
    }

    /*TODO: for now, hiding button if in 'view created schedule' view. need to implement edit capabilities.*/
    if($_SESSION['pageID'] == 'NewView')
    {
        echo "
                <button type='submit' id='submit-schedule-button' class='scheduleButton' value='newSchedule' name='newScheduleSubmit'>
                    SUBMIT
                </button>";
    }


    echo "
                 </div>
            </div>
        </form>";