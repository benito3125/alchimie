<?php
session_start(); // Assurez-vous d'appeler session_start() au début de chaque script utilisant les sessions

// Déconnecter l'utilisateur
session_unset();
session_destroy();

// Rediriger vers la page principale ou une autre page après la déconnexion
header("Location: index.php");
exit();
?>