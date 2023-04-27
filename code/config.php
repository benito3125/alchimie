<?php
    // Informations d'identification
    define('DB_SERVER', 'bb621576-002.eu.clouddb.ovh.net:35512');
    define('DB_USERNAME', '***');
    define('DB_PASSWORD', '***');
    define('DB_NAME', 'repas');
    
    // Connexion à la base de données MySQL 
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Vérifier la connexion
    if($conn === false){
        die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
    }
?>