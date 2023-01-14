<!--TODO: change this to .php once testing with the DB-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--    Bootstrap Styles and Main Styles-->
    <link href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main_styles.css">
    
    <title>Advise-It Tool</title>

</head>

<body>

    <header>
        <nav class="navbar mb-3 pt-0 pb-0">
            <a class="navbar-brand p-2 col-3 col-lg-2 col-xl-1 me-0" href="https://www.greenriver.edu/">
                <img id="centerImg" class="img-fluid" src="imgs/logo.png" alt="Green River College logo" />
            </a>
            <section class="col-9 col-lg-10 col-xl-11">
                <h1 class="ps-3">
                    Advise-It Tool
                </h1>
            </section>
    
        </nav>
    </header>
    
    <main>

        <!--TESTING DB CONNECTION-->
        <?php
        include("php/db_connection.php")
        ?>

        <div class="container-fluid">
        
            <div class="row justify-content-center">
            
                <!--want to dynamically create all 4 quarters using PHP loop. currently doing this manually during development-->
                <div class="col-10 col-md-5 shadow quarter-box m-2">
                    <h2>Fall</h2>
                    <p>CLASSES</p>
                </div>
                <div class="col-10 col-md-5 shadow quarter-box m-2">
                    <h2>Winter</h2>
                    <p>CLASSES</p>
                </div>
                <div class="col-10 col-md-5 shadow quarter-box m-2">
                    <h2>Spring</h2>
                    <p>CLASSES</p>
                </div>
                <div class="col-10 col-md-5 shadow quarter-box m-2">
                    <h2>Summer</h2>
                    <p>CLASSES</p>
                </div>
        
            </div>
    
        </div>
    </main>
    
    
    
    <!--TODO: decide whether the JS for BS will be used or not-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../controller/js/general_js.js"></script>
</body>
</html>