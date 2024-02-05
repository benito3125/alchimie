<?php
session_start(); // Assurez-vous d'appeler session_start() au début de chaque script utilisant les sessions

require_once 'mysql.php';
//require_once 'resources_acces.php';

$NO_PERM_PAGES = array(
    '/index.php',
    '/login.php',
    '/register.php'
);

// User Roles creation
$ROLES = array(
    'admin' => array(
        '/connecte.php',
        '/admin.php',
        '/faq.php',
        '/onboarding.php',
        '/stats.php',
        '/stats_repas.php',
        '/stats_fournitures.php',
        '/tableau_tshirt.php',
        '/serre_tete.php',
        '/neck_warme.php',
        '/liste_repas_pris.php',
        '/liste_repas_non_pris.php',
        '/link.php'
    ),
    'accueil' => array(
        '/connecte.php',
        '/faq.php',
        '/stats.php',
        '/stats_repas.php',
        '/stats_fournitures.php',
        '/tableau_tshirt.php',
        '/serre_tete.php',
        '/neck_warme.php',
        '/onboarding.php'
    ),
    'comite' => array(
        '/connecte.php',
        '/stats.php',
        '/faq.php',
        '/stats_repas.php',
        '/stats_fournitures.php',
        '/tableau_tshirt.php',
        '/serre_tete.php',
        '/neck_warme.php',
        '/liste_repas_pris.php',
        '/liste_repas_non_pris.php'
    )
);

class user{
    private $id = null;
    private $role = null;

    function __construct(int $id, string $role) {
        $this -> id = $id;
        $this -> role = $role;        
    }

    public function getId() {
        return $this -> id;        
    }

    public function getUserRole() {
        return $this -> role;        
    }
}
$userRole ="";
$current_page = $_SERVER['PHP_SELF'];
if (!in_array($current_page, $NO_PERM_PAGES)) {
    if (isset($_SESSION) && isset($_SESSION['user'])){
        $userRole = $_SESSION['user']->getUserRole();
        $accessPages = $ROLES[$userRole];
        if (!in_array($current_page, $accessPages)) {
            header("Location: connecte.php");
            exit();
        }
    }
    else {
        header("Location: index.php");
        exit();
    }
}
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
                    <?php if ($userRole === 'accueil' || $userRole === 'admin'): ?>
                    <li class="">
                        <a href="onboarding.php">Onboarding</a>
                    </li>
                    <?php endif; ?>
                    <?php if ($userRole === 'comite' || $userRole === 'admin' || $userRole === 'accueil'): ?>
                    <li class="">
                            <a href="stats.php">Statistiques</a>
                    </li>
                    <?php endif; ?>
                    <li class="">
                            <a href="faq.php">FAQ</a>
                    </li>
                    <?php if ($userRole === 'admin'): ?>
                    <li class="">
                            <a href="admin.php">Administration</a>
                    </li>
                    <?php endif; ?>
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
