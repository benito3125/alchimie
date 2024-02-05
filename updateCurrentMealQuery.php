<?php

 // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
	include "mealsServices.php";
   if (isset($_SESSION["username"])) 
    {
          if (isset($_POST["mealCode"]))
          {
                echo "mealCode est setté = reset current meal";
                resetCurrentMeal();
                 updateCurrentMeal($_POST["mealCode"]);
                $appliedMeal = getCurrentMeal();
                return $appliedMeal[0]["label"];
          }
          else
          {
                  echo "mealCode n'est as setté";
          }
    }
    else
    {
        header("Location: login.php");
       exit();
    }


  
function updateCurrentMeal($newCurrentMealCode)
{
        global $conn;
         $sql = "UPDATE meals SET currentlyChecking=1 WHERE code='".$newCurrentMealCode."'";
         echo "requete ".$sql." en cours...";

         // Exécution de la requête SQL
         $result = $conn->query($sql);
         //echo "req sql ok";
}

function resetCurrentMeal()
{
         global $conn;
         $sql = "UPDATE meals SET currentlyChecking=0 WHERE currentlyChecking=1";
         echo "requete ".$sql." en cours...";

         // Exécution de la requête SQL
         $result = $conn->query($sql);
         //echo "req sql ok";
}



?>									