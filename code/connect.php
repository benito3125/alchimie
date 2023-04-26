<!DOCTYPE html>
<html>

    <!-- Inclusion des scripts et liens -->
    <?php include"link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style.css" />
    </head>
      <!-- Navigation -->
      <?php include"nav.php"?>
    <!-- Header -->
    <?php include"header.php"?>
<body>
<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

	if(isset($_SESSION["username"])){
?>


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
                    <button type="button" value="Saisir " name="saisir" class="box-button" onClick="window.location.href='stats.php'">Statistiques</button>&nbsp;
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

     header("Location: login.php");

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