<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Repas des Bénévoles">
    <meta name="keywords" content="repas, jeu, festival, toulouse, alchimie, bénévoles">
    <title>Repas des Bénévoles</title>
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/themes/batblog/css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/56fa629212.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/bootstrap.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/themes/batblog/js/theme.js"></script>
    <meta name="generator" content="Batflat" />
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.css">
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.js"></script>
</head>


<!-- Navigation -->
<nav class="navbar navbar-default navbar-custom">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Header -->
<header class="intro-header"
    style="background-image: url('https://benevoles.alchimiedujeu.fr/themes/batblog/img/default-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Page de connexion</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<body>

    <div class="container">
        <div class="row" style="position: right">
            <div class="bubble-element Group" style="position: center"
                style="box-sizing: border-box; position:inherit; z-index: 3; top: 25px; width: 283px; left: 0px; border-style: solid; border-width: 2px; border-color: rgb(107, 107, 107); border-radius: 0px; opacity: 1; height: 221px;">
                <div class="bubble-r-line" style="margin-top: 16px; height: 30px;">
                    <div class="bubble-r-box" style="height: 30px; left: 52px; width: 179px;">
                        <h3 class="bubble-element Text"
                            style="white-space: pre-wrap; position: relative; box-sizing: border-box; z-index: 2; top: 0px; width: 179px; left: 0px; height: 30px; overflow: visible; font-family: Barlow; font-size: 16px; font-weight: 400; color: rgb(9, 23, 71); text-align: center; line-height: 1.5; border-radius: 0px; opacity: 1;">
                            <div class="content">Log in</div>
                        </h3>
                    </div>
                </div>
                <div class="bubble-r-line" style="margin-top: 2px; height: 40px;">
                    <div class="bubble-r-box" style="height: 40px; left: 16px; width: 251px;"><input type="login" name="login" class="" tabindex="5" placeholder="login" maxlength="" style="position: relative; box-sizing: border-box; z-index: 3; height: 40px; top: 0px; width: 251px; left: 0px; border: 1px solid rgb(189, 189, 189); background-color: rgb(252, 252, 252); border-radius: 5px; font-family: Barlow; font-size: 16px; font-weight: 500; color: rgb(107, 107, 107); padding: 0px 10px; opacity: 1;">
                    </div>
                </div>
                <div class="bubble-r-line" style="margin-top: 15px; height: 40px;">
                    <div class="bubble-r-box" style="height: 40px; left: 16px; width: 251px;"><input type="password" name="password"
                            autocomplete="new-password" class="" tabindex="6"
                            placeholder="Password" maxlength=""
                            style="position: relative; box-sizing: border-box; z-index: 4; height: 40px; top: 0px; width: 251px; left: 0px; border: 1px solid rgb(189, 189, 189); background-color: rgb(252, 252, 252); border-radius: 5px; font-family: Barlow; font-size: 16px; font-weight: 500; color: rgb(107, 107, 107); padding: 0px 10px; opacity: 1;">
                    </div>
                </div>
                <div class="bubble-r-line" style="margin-top: 18px; height: 40px;">
                    <div class="bubble-r-box" style="height: 40px; left: 14px; width: 251px;"><button
                            class="bubble-element Button clickable-element" tabindex="7"
                            style="padding: 0px; cursor: pointer; background: none rgb(134, 23, 115); border: none; text-align: center; position: center; box-sizing: border-box; z-index: 6; height: 40px; top: 0px; width: 251px; left: 0px; font-family: Barlow; font-size: 16px; font-weight: 600; color: rgb(255, 255, 255); letter-spacing: 2px; line-height: 1; border-radius: 5px; opacity: 1; transition: background 200ms ease 0s; box-shadow: none;">Log In</button></div>
                </div>
            </div>


        </div>
    </div>
    <hr>
</body>

<?php
session_start()
if(isset($_POST['login']) && isset($post['password']))
{
    //connexion à la base de données 
    $db_username = 'admin';
    $db_password = 'Benjamin31';
    $db_name = 'repas';
    $db_host = 'bb621576-001.eu.clouddb.ovh.net:35466';
    $db = mysqli_connect( $db_host,$db_name, $db_username, $db_password)
    or die('could not connect to database');
}
$db = new PDO
$username = mysqli_real_escape_string($db,htmlspecialchars($_POST['login'])); 
$password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));
mysqli_close($db); // fermer la connexion
?>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p class="copyright text-muted">
                    Copyright 2023 &copy; Association Alchimie du Jeu.
                </p>
            </div>
        </div>
    </div>
</footer>


</html>