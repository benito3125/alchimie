<?php

/*if(isset($_POST['scannedId']))
{
echo "{\"scannedId\": 1}";
}
else
{
echo "{\"scannedId\": 0}";
}*/	


       include "config.php";

       session_start();
       //echo "username OK";
       if(isset($_POST['scannedId']))
       {
              //echo "<BR>id is set !!";
              // Récupération de l'ID depuis la requête Ajax
              $id = $_POST['scannedId'];
           
              //echo "get OK !!!!!";
              // Préparation de la requête SQL
              $sql = "SELECT * FROM benevoles_2024 WHERE ID = ".$id;
              //echo $sql;
              // Exécution de la requête SQL
              
              $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
              if ($conn->connect_error) 
              {
                     echo "Connexion bdd en echec";
                     die("Connection failed: " . $conn->connect_error);
              } 
              else 
              {
                     //echo "Connexion bdd réussie";
              }

             
              $items = array();
              $result = $conn->query($sql) ;


              foreach ($result as $row) 
              {
                     array_push($items, $row);
              }

           
              // Conversion du record en format JSON et envoi de la réponse
              header('Content-Type: application/json');
              print json_encode($items);
//echo "{\"id\": ".count(items)."}";

        }
        else
        {
            echo "<BR> id is not set";
            //on retourne une erreur si le parametre scannedId n'est pas setté
            exit(1);
        }
        // Fermeture de la connexion à la base de données
        $conn->close();
?>			