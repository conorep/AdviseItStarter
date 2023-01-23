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
//    var_dump($_SESSION);

        $_SESSION['pageID'] = 'HomeView';
        $modelCalls->unsetVars($quartersArr);

        echo "

        <h1 class='text-center text-decoration-underline'>HOME PAGE</h1>
        <br/>
        <div class='container-fluid row justify-content-center'>
            <h3 class='text-center'>Welcome!</h3>

            <div class='col-6 mb-2'>
                <p class='text-center'>This is your home page. Please click a button to select what you're here for:</p>
                <ul class='list-group list-group-flush'>
                    <li class='list-group-item'><em>HOME:</em> You're here.</li>
                    <li class='list-group-item'><em>NEW SCHEDULE:</em> Create a new schedule plan.</li>
                    <li class='list-group-item'><em>VIEW SCHEDULES:</em> View all existing plans.</li>
                </ul>
            </div>

        </div>";
    } else
    {
        include('retrieve.php');
    }
