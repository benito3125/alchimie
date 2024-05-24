<!DOCTYPE html>
<?php
	include "config.php";
	include "header.php";

	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion


	if(isset($_SESSION))
	{ 

		// Vérification de la connexion
		$conn=connectToDatabase();
		if ($conn->connect_error)  
		{
			echo "Connexion bdd en echec";
			//die("Connection failed: " . $conn->connect_error);
		} 
		else 
		{
			echo "Connexion bdd réussie";
		}
		//echo "session setteee";	
	}
	else
	{
		//echo "session unset !";
		header('Location: http://tools.alchimiedujeu.fr/');
	}

?>

<html>

	<!-- Inclusion des scripts et liens -->
	<?php include "link.php"?>
	<?php include "meals.php"?>
	<head>
		<link rel="stylesheet" href="./css/style-qrcode.css?timestamp=<?php echo "".time(); ?>" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="instascan.min.js"></script>
		
 

	</head>
    <!-- Header -->


<body>
	<script>

		let cameraIndex = 0;
		let currentScannerInstance;
		
		function afficherChoix() 
		{
			var choix = document.getElementById("listeDeroulante").value;
			
			let displayedText;
			if (choix =="RJM")
			{
				displayedText= "Acces au repas du JEUDI_MIDI";
			}
			else if (choix =="RVM")
			{
				displayedText= "Acces au repas du VENDREDI_MIDI";
			}
			else if (choix =="RVS")
			{
				displayedText= "Acces au repas du VENDREDI_SOIR";
			}
			else if (choix =="RSM")
			{
				displayedText= "Acces au repas du SAMEDI_MIDI";
			}
			else if (choix =="RDM")
			{
				displayedText= "Acces au repas du DIMANCHE_MIDI";
			}
			document.getElementById('resultatChoix').textContent = displayedText;
		}


		function getMealStatus(benevole, currentMeal)
		{
			let mealStatus;
			if (currentMeal=="RJM")
			{
				mealStatus = benevole.RJM;
			}
			else if (currentMeal=="RVM")
			{
				mealStatus = benevole.RVM;
			}
			else if (currentMeal=="RVS")
			{
				mealStatus = benevole.RVS;
			}
			else if (currentMeal=="RSM")
			{
				mealStatus = benevole.RSM;
			}
			else if (currentMeal=="RDM")
			{
				mealStatus = benevole.RDM;
			}
			return mealStatus;
		}

		function startScanner() 
		{
			// Créer une instance du scanner

			if (typeof currentScannerInstance == "undefined")
			{
			}
			else
			{
				currentScannerInstance.stop();
			}
			
			currentScannerInstance = new Instascan.Scanner({ video: document.getElementById('preview') });
			
			currentScannerInstance.addListener('scan', function (content) 
			{
			        // Récupérer l'ID du QR code scanné
				let scannedId = "'"+content+"'";
				//alert("on a scanne l'id "+scannedId);
				var request = $.ajax({
					url: "query.php", // URL du script PHP pour la requête SQL
					method: "POST",
					dataType: "json",
					data: "scannedId="+scannedId,
					});

		
				//definition de la callback success de requete ajax query
				request.done( 
					function(data)
					{
						//alert("data="+data);
						let benevoles= JSON.parse(JSON.stringify(data));
						//alert(benevoles.length+" benevevoles trouves");

						
						
						let currentMeal = document.getElementById("listeDeroulante").value;


						let mealStatus
						if (benevoles.length == 0 || benevoles.length > 1)
						{
							mealStatus = 0;
						}
						else 
						{
							mealStatus = getMealStatus(benevoles[0],currentMeal) ;

						}



						//alert("status repas = "+mealStatus);
						if (mealStatus == 1)
						{
							let vegeValue = benevoles[0].vege;
							// Si la valeur RJM est égale à 1
							if (vegeValue == 1)
							{
								// Si la valeur végé est égale à 1
								document.body.style.backgroundColor = "green";
								document.getElementById('resultatScan').innerHTML = "Repas validé (végé)";
							}
							else
							{
								// Si la valeur végé est égale à 0
								document.body.style.backgroundColor = "orange";
								document.getElementById('resultatScan').innerHTML = "Repas validé";
							}
	
							// Passer la valeur RJM à 2 dans la base de données
							var updateRequest = $.ajax({
								method: "POST",
								url: "update.php",
								data: "id="+benevoles[0].ID+"&meal="+currentMeal,
							});
							
							updateRequest.done
							(
								function(response)
								{
									//alert("la requete d'update a reussi");
									// Afficher la réponse de la requête d'update
									console.log(response);
								}
							);
							
							updateRequest.fail
							(
								function(xhr, status, error)
								{
									alert("echec de la requete d'update");
									console.error(error);
								}
							);

							alert("Qr Code validé !");

						}
						else if (mealStatus == 0)
						{
							// Si la valeur RJM est égale à 0
							document.body.style.backgroundColor = "red";
							document.getElementById('resultatScan').innerHTML= "Pas de repas prévu";
							alert("ACCES REFUSE !");
						}
						else
						{
							// Si la valeur RJM est égale à 2
							document.body.style.backgroundColor = "red";
							document.getElementById('resultatScan').innerHTML = "Repas déjà validé";
							alert("ACCES REFUSE !");
						}		
					}
				);
				//fin de la callback success

				//definition de la callback error de la requete ajax query
				request.fail(
					function(xhr, status, error)
					{
						alert("La requête s'est terminée en échec. Infos : " + JSON.stringify(error));
						alert("Une erreur s'est produite lors de la requête Ajax: " + status + " " + error);
					}
				);
				//fin de la callback error				
	
			});
		
			




			Instascan.Camera.getCameras().then(
				function (cameras) 
				{
					alert("le telephone dispose de "+cameras.length+" cameras");
					if (cameraIndex + 1 == cameras.length)
					{
						cameraIndex = 0;
					}
					else 
					{
						cameraIndex = cameraIndex +1;
					}
					alert("on lance la camera n°"+cameraIndex);
					currentScannerInstance.start(cameras[cameraIndex ]);
				}
			).catch(function (e) 
				{

					alert("erreur = "+e);
					console.error(e);
				}
			);



		}//fin fonction start scanner

	</script>


<font color='black'><b>
	<div>
		<p>
			A fin de pouvoir commencer le Scan des repas, veuillez sélectionner le jour.
		</p>
		<form>
		<label for="listeDeroulante">Choisissez une option :</label>
			<select id="listeDeroulante" onchange="afficherChoix()">
				<option value="RJM">Jeudi Midi</option>
				<option value="RVM">Vendredi Midi</option>
				<option value="RVS">Vendredi Soir</option>
				<option value="RSM">Samedi Midi</option>
				<option value="RDM">Dimanche Midi</option>
			</select>
			<br>
		</form>


		<div id="resultatChoix">Acces au repas du JEUDI_MIDI</div>
		

<div id="resultatScan"></div>
		<div id="scanner" >
			<video id="preview" ></video>
			<canvas id="qr-canvas" ></canvas>
		</div>
		<h1>Scanner de QR code</h1>
		<div>
			<button onclick="startScanner()">Démarrer le scanner</button>
		</div>


		
		
	<div>		
</font color='black'></b>

<!-- Footer -->
<?php /*include"footer.php"*/?>
</body>
</html>																				