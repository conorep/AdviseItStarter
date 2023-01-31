<?php
    session_start();

    /**
     * This file contains the code for the "home" view.
     * @version 1.0
     * @author Conor O'Brien
     */

    if(!isset($_SESSION['scheduleToken']))
    {
        include('../include/object_creation.php');

        $_SESSION['pageID'] = 'HomeView';
        $controllerObject->unsetVars($quartersArr);

        echo "
        <br/>
        <div class='container-fluid row justify-content-center'>
            <h3 class='text-center'>Welcome!</h3>

            <div class='col-7 mb-2'>
                <p class='text-center'>
                    Click a button above to navigate to your desired page or enter your schedule ID below.
                </p>

            </div>";
            
        include('../include/get_schedule_form.php');

    echo "
        </div>";
    } else
    {
        include('retrieve.php');
    }
