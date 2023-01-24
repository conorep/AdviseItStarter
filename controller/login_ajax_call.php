<?php
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    /**
     * This file handles the login or check login function for site administrators.
     * @version 1.0
     * @author Conor O'Brien
     */
    include('../include/object_creation.php');

    /*TODO: error handling for improper logins!!!!*/
    /**
     * The POST ajax call creates attempts an admin login.
     */
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(!empty($_POST))
        {
            $logEmail = $_POST['AdminEmail'];
            $logPass = $_POST['AdminPass'];
            if($modelCalls->adminLogin($logEmail, $logPass))
            {
                echo '<script>console.log("you logged in!")</script>';
            };
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if(!empty($_GET))
        {
            if($_GET['LogGet'] == 'yes')
            {
                $modelCalls->logout();
            }
        }
    }
