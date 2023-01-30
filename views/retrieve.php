<?php
    session_start();

    /**
     * This file contains the code for the "retrieve" view.
     * It utilizes PHP to dynamically generate HTML output for the planning view
     *  and retrieves existing schedule data from the DB.
     * @version 1.0
     * @author Conor O'Brien
     */

    include('../include/object_creation.php');
//    var_dump($_POST, $_GET, $_SESSION);
    
    $_SESSION['pageID'] = 'RetrieveView';
    $controllerObject->unsetVars($quartersArr);

    include('../include/schedule_form.php');