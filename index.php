<?php
    session_start();
    
    if(isset($_SESSION['scheduleToken']))
    {
        unset($_SESSION['scheduleToken']);
    }
?>
<!--
    This is the application page containing HTML for the general application view.
    @version 1.0
    @author Conor O'Brien
-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--favicon-->
    <link rel="icon" type="image/x-icon" href="imgs/logo.png">

    <!--    Bootstrap Styles and Main Styles-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main_styles.css" type="text/css">
    <link rel="stylesheet" href="css/button_styles.css" type="text/css">
    <link rel="stylesheet" media="print" href="css/print_styles.css" type="text/css">
    
    <title>Advise-It Tool</title>
    
    <?php
        /*TODO: make this dynamic. this app could be deployed elsewhere and having to edit this exactly is a bad plan*/
        if(strlen($_SERVER['REQUEST_URI']) >= 19)
        {
            $substrURI = substr($_SERVER['REQUEST_URI'], 19);
            if(strlen($substrURI) == 6)
            {
                $_SESSION['scheduleToken'] = $substrURI;
            }
        }

    ?>

</head>

<body>
    <header class="no-print-section">
        <nav class="navbar mb-3 pt-0 pb-0">

            <a class="navbar-brand p-2 col-3 col-lg-2 col-xl-1 me-0" href="https://www.greenriver.edu/">
                <img id="centerImg" class="img-fluid" src="imgs/logo.png" alt="Green River College logo"/>
            </a>

            <section class="col-9 col-lg-10 col-xl-11">
                <h2 class="ps-3 inline-block">
                    <img id="logoImg" src="imgs/adviseitlogo.png" alt="Advise-It Tool Name" />
                </h2>

                <!--TODO: think about how many buttons I will have here. enough to benefit from a view dynamically
                        generating them...?-->
                <div class="float-end me-sm-3">

                    <button id="home-view-button" class="scheduleButton viewButton">
                        HOME
                    </button>

                    <button id="new-view-button" class="scheduleButton viewButton">
                        NEW SCHEDULE
                    </button>

                    <?php
                        /*if admin logged in, show view schedules button. if not, show admin login button*/
                        if(isset($_SESSION['adminLogged']))
                        {
                            if($_SESSION['adminLogged'])
                            {
                                echo "
                                <button id='retrieve-view-button' class='scheduleButton viewButton'>
                                    VIEW SCHEDULES
                               </button>
                               <button id='logout-button' class='scheduleButton viewButton'>
                                    LOGOUT
                                </button>
                               ";
                            }
                        } else
                        {
                            echo "
                                <button id='admin-view-button' class='scheduleButton viewButton' type='button'
                                        data-bs-toggle='offcanvas' data-bs-target='#admin-login' aria-controls='admin-login'>
                                    ADMIN
                                </button>
                                <div class='offcanvas offcanvas-end' tabindex='-1' id='admin-login' aria-labelledby='admin-login-label'>
                                    <div class='offcanvas-header'>
                                        <h5 class='offcanvas-title fw-bold' id='admin-login-label'>Administrator Login</h5>
                                        <button type='button' class='btn-close text-reset' data-bs-dismiss='offcanvas' aria-label='Close'></button>
                                    </div>
                                    <div class='offcanvas-body'>
                                        <div>
                                            <form id='adminLoginSubmit' method='post'>
                                            
                                                <label class='form-label fw-bold' for='admin-email-input'>Admin Email or Username</label>
                                                <input class='form-control' name='AdminEmail' id='admin-email-input' 
                                                    type='text' placeholder='Enter account email or username...' autocomplete='on'>
                                                    
                                                <label class='form-label mt-3 fw-bold' for='admin-pass-input'>Password</label>
                                                <input class='form-control' name='AdminPass' id='admin-pass-input' 
                                                    type='password' placeholder='Enter account password...' 
                                                    autocomplete='on'>
                                                    
                                                <button id='admin-login-button' class='scheduleButton viewButton mt-2 me-2 float-end'
                                                    type='submit'>LOGIN</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>";
                        }
                    ?>

                </div>

            </section>
        </nav>


    </header>
    
    <h3 class="hide-for-print print bottom-margin"><em>Green River College</em> <strong>ADVISE-IT SCHEDULE</strong></h3>
    
    <main id="mainContent">
        
        <!--PHP content here-->
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!--TODO: decide whether the JS for BS will be used or not-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/general_js.js"></script>
</body>
</html>