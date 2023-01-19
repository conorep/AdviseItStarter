<?php
    session_start();

    /**
     * This file contains the code for the "new plan" view.
     * It utilizes PHP to dynamically generate HTML output for the planning view.
     * @version 1.0
     * @author Conor O'Brien
     */

    include('../include/object_creation.php');
//    var_dump($_SESSION);

    $_SESSION['pageID'] = 'NewView';
    $modelCalls->unsetVars($quartersArr);

    include('../include/schedule_form.php');