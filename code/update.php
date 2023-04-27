<?php// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
    if (isset($_SESSION["username"])) {

        // Préparation de la requête SQL pour mettre à jour le nom dans la table
        $requete = "UPDATE benevoles SET RJM='2' WHERE ID=$id";

        // Exécution de la requête SQL
        if (mysqli_query($connexion, $requete)) {
            echo "Le nom a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du nom : " . mysqli_error($connexion);
        }
        
        // Fermeture de la connexion à la base de données
        $conn->close();

        //Si l'utilisateur n'est pas connecté
    }else{
        header("Location: login.php");
        exit();
    }
?>