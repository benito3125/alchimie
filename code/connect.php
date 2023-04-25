<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css" />
    <base href="/"/>
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
<body>
<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

	if(isset($_SESSION["username"])){
?>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-custom">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
						<a href="logout.php">Déconnexion</a>
                    </li>
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
				<h1>Bienvenue <?php echo $_SESSION['username']; ?>!</h1>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="container">
        <div class="row">
            <div>
                <h2>Bienvenue sur l'outil de gestion des repas des bénévoles du Festival Alchimie du jeu de
                    Toulouse.
                </h2>
                <p>Via cette interface, vous aurez la possibilité soit de: <br>
                    - saisir le passage des repas;<br>
                    - visualiser en temps réel l'avancement des passages des bénévoles à la cantine;<br>
                    - ajouter des repas à un bénévole donné.:&nbsp;
                </p>
                <div position="relative">
                    <h1 class="box-title">Que souhaitez vous faire?</h1>
                    <button type="button" value="Saisir " name="saisir" class="box-button" onClick="window.location.href='profil.php'">Statistiques</button>&nbsp;
                    <button type="button" value="Saisir " name="saisir" class="box-button" onClick="window.location.href='saisir.php'">Saisir</button>&nbsp;
                    <button type="button" value="Update " name="update" class="box-button">Update</button>
                </div>
            </div>
            <!-- Sidebar -->
            <div>
        </div>
    </div>
    <div>
    </div>
<?php 
}else{

     header("Location: index.php");

     exit();
}

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
</body>

</html>