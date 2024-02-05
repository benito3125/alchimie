<?php

    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";

function getMeals()
{
          global $conn;

         $sql = "SELECT * FROM meals ORDER BY startOfService ASC";
         //echo "requete ".$sql." en cours...";

         // Exécution de la requête SQL
         $result = $conn->query($sql);
         //echo "req sql ok";
         $rows = array();
         while ($row = $result->fetch_assoc()) 
         {
              $rows[] = $row;
             //echo "nouvel enregistrement";
         } 
         return $rows;
}


function getCurrentMeal()
{
          global $conn;

         $sql = "SELECT * FROM meals WHERE currentlyChecking=1";
  //       echo "requete ".$sql." en cours...";

         // Exécution de la requête SQL
         $result = $conn->query($sql);
//         echo "req sql ok";
         $rows = array();
         while ($row = $result->fetch_assoc()) 
         {
              $rows[] = $row;
             //echo "nouvel enregistrement";
         } 
         return $rows;
}



?>						