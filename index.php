<?php
    session_start();

    /*TODO: add route handling for token URI input*/
    include("controller/route.php");
    $routing = new Route();
    $routing->addRoute("/{id}", "/");
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--favicon-->
    <link rel="icon" type="image/x-icon" href="imgs/logo.png">

    <!--    Bootstrap Styles and Main Styles-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main_styles.css">
    <link rel="stylesheet" href="css/buttonStyle.css">
    
    <title>Advise-It Tool</title>

</head>

<body>

    <header>
        <nav class="navbar mb-3 pt-0 pb-0">

            <a class="navbar-brand p-2 col-3 col-lg-2 col-xl-1 me-0" href="https://www.greenriver.edu/">
                <img id="centerImg" class="img-fluid" src="imgs/logo.png" alt="Green River College logo"/>
            </a>

            <section class="col-9 col-lg-10 col-xl-11">
                <h2 class="ps-3 inline-block">
                    Advise-It Tool
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

                    <button id="retrieve-view-button" class="scheduleButton viewButton">
                        RETRIEVE SCHEDULE
                    </button>
                </div>

            </section>

        </nav>
    </header>
    
    <main id="mainContent">
        <!--PHP content here-->
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!--TODO: decide whether the JS for BS will be used or not-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/general_js.js"></script>
</body>
</html>