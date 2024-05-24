<?php
// Initialiser la session
	
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
	if(isset($_POST['id']) && isset($_POST['meal']))
	{
		$id = $_POST['id'];
		$currentMeal = $_POST['meal'];

		// Préparation de la requête SQL pour mettre à jour le nom dans la table
		$requete = "UPDATE benevoles_2024 SET ".$currentMeal."='2' WHERE ID='".$id."'";

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

		echo "$requete";

		$result = $conn->query($requete) ;


		// Fermeture de la connexion à la base de données
		$conn->close();
	}
?>