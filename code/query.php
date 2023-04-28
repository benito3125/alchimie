<?php

    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
    if (isset($_SESSION["username"])) 
    {
       //echo "username OK";
        if(isset($_POST['scannedId']))
        {
            //echo "<BR>id is set !!";
            // Récupération de l'ID depuis la requête Ajax
           $id = $_POST['scannedId'];
            //echo "get OK !!!!!";
            // Préparation de la requête SQL
           $sql = "SELECT * FROM benevoles WHERE ID = $id";

            // Exécution de la requête SQL
            $result = $conn->query($sql);
          // echo "req sql ok";

           $row = $result->fetch_assoc();
            //echo "fetch ok";
            // Conversion du record en format JSON et envoi de la réponse
            header('Content-Type: application/json');
           // echo "on va encoder le json";
            print json_encode($row);
            //echo "le json est encode";
        }
        else
        {
            //echo "<BR> id is not set";
            //on retourne une erreur si le parametre scannedId n'est pas setté
            exit(1);
        }
        // Fermeture de la connexion à la base de données
        $conn->close();

        //Si l'utilisateur n'est pas connecté
    }
    else
    {
        header("Location: login.php");
        exit();
    }

?>		