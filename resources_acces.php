<?php
session_start(); // Assurez-vous d'appeler session_start() au début de chaque script utilisant les sessions

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
        '/liste_repas_non_pris.php'
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
