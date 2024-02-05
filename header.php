<?php
session_start(); // Assurez-vous d'appeler session_start() au début de chaque script utilisant les sessions

// Inclure le fichier mysql.php qui contient la fonction getUserRole
require_once 'mysql.php';
require_once 'resources_acces.php';
//$_SESSION['user']->getRole();

//print_r ($_SESSION);
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
            <a class="navbar-brand" href="https://benevoles.alchimiedujeu.fr">Bénévoles Alchimie du Jeu</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Boutons connecté -->
                    <li class="">
                        <a href="connecte.php">Accueil</a>
                    </li>
                    <li class="">
                        <a href="onboarding.php">Onboarding</a>
                    </li>
                    <li class="">
                            <a href="stats.php">Statistiques</a>
                    </li>
                    <li class="">
                            <a href="faq.php">FAQ</a>
                    </li>
                    <li class="">
                            <a href="admin.php">Administration</a>
                    </li>
                    <li>
                        <a href="logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <!-- Liens de connexion et d'inscription -->
                    <li>
                        <a href="login.php">Connexion</a>
                    </li>
                    <li>
                        <a href="register.php">Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<header class="intro-header" style="background-image: url('https://benevoles.alchimiedujeu.fr/themes/batblog/img/default-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Outils de l'Accueil de l'Alchimie du Jeu de Toulouse.</h1>
                </div>
            </div>
        </div>
    </div>
</header>
