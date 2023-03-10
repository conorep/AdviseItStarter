<?php
    session_start();

    /**
     * This file contains calls for the form HTML for schedule submission/view/updates.
     * @version 1.0
     * @author Conor O'Brien
     */


    /*call the unique token generator method if $_SESSION['pageID'] is 'NewView'
            or retrieve existing info if $_SESSION['pageID'] is 'RetrieveView*/
    if($_SESSION['pageID'] == 'NewView')
    {
        if(isset($_SESSION['scheduleToken']))
        {
            unset($_SESSION['scheduleToken']);
        }
        include("form_opening.php");
        $controllerObject->displayUniqueToken();

        include("schedule_html.php");

    } else if($_SESSION['pageID'] == 'RetrieveView')
    {
        /*if there's a scheduleToken, load data from freshly-created schedule*/
        /*if not, load a bunch of links that will allow retrieval of old schedules for viewing and editing*/
        if($_SESSION['scheduleToken'] != '')
        {
            include("form_opening.php");
            $controllerObject->displayCreatedPlan($_SESSION['scheduleToken']);
            if($_SESSION['planRetrieved'])
            {
                include("schedule_html.php");
            }

            /*unset scheduleToken and set planData back to '' when done with displaying created plan*/
            unset($_SESSION['scheduleToken']);
            unset($_SESSION['planRetrieved']);
            $_SESSION['planData'] = '';
        } else
        {
            include("existing_schedules.php");
        }

    } else
    {
        echo"I DON'T KNOW HOW YOU GET HERE!";
    }