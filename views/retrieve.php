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
    $_SESSION['pageID'] = "RetrieveView";

    include('../include/schedule_form.php');