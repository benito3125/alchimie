<?php// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
    if (isset($_SESSION["username"])) {
        if( isset( $_POST['scannedId'] ) ){
            // Récupération de l'ID depuis la requête Ajax
            $id = $_POST['scannedId'];
            
            // Préparation de la requête SQL
            $sql = "SELECT * FROM benevoles WHERE ID = $id";

            // Exécution de la requête SQL
            $result = $conn->query($sql);

            // Création d'un tableau pour stocker les résultats
            $rows = array();

            // Boucle à travers les résultats et stockage dans le tableau
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            // Conversion du tableau en format JSON et envoi de la réponse
            header('Content-Type: application/json');
            echo json_encode($rows);
        }
        // Fermeture de la connexion à la base de données
        $conn->close();

    //Si l'utilisateur n'est pas connecté
    }else{
        header("Location: login.php");
        exit();
    }

?>