<?php
    session_start();

    /**
     * This file contains the form HTML for schedule submission/view/updates
     * @version 1.0
     * @author Conor O'Brien
     */

    echo $_SESSION['pageID'];
    echo '<br/>' . $_SESSION['scheduleToken'];
    echo "        
            <form id='scheduleSubmit' action='' method='post'>
                <div class='container-fluid'>
                    <div class='row justify-content-center'>";

    /*call the unique token generator method if $_SESSION['pageID'] is 'NewView'
            or retrieve existing info if $_SESSION['pageID'] is 'RetrieveView*/
    if($_SESSION['pageID'] == 'NewView')
    {
        $modelCalls->displayUniqueToken();
    } else if($_SESSION['pageID'] == 'RetrieveView')
    {
        if($_SESSION['scheduleToken'] != '')
        {
            $modelCalls->displayCreatedPlan($_SESSION['scheduleToken']);
            $_SESSION['scheduleToken'] = '';
            $_SESSION['planData'] = '';
        }
    } else
    {
        echo"I DON'T KNOW HOW YOU GET HERE!";
    }

    foreach ($quartersArr as $quarter)
    {
        echo "                    
                        <div class='col-10 col-md-5 shadow quarter-box m-2 p-2'>
                            <h2>" . $quarter . "</h2>
                            <label for='" . $quarter . "'>CLASSES</label>
                            <textarea class='quarterInput form-control' id='" . $quarter . "' rows='5' name='" . $quarter . "'></textarea>
                        </div>";
    }

    echo "
                        <button type='submit' id='submit-schedule-button' class='scheduleButton' value='newSchedule' name='newScheduleSubmit'>
                            SUBMIT
                        </button>";

    echo "            </div>
                </div>
            </form>";