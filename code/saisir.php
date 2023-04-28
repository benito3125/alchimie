<!DOCTYPE html>
<html>

	<!-- Inclusion des scripts et liens -->
	<?php include "link.php"?>
	<head>
		<link rel="stylesheet" href="../css/style-qrcode.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="instascan.min.js"></script>
		<html>
 

	</head>
    <!-- Navigation -->
    <?php include "nav.php"?>
    <!-- Header -->
    <?php include "header.php"?>

<body>
<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
	if(isset($_SESSION["username"])){ 
		// Vérification de la connexion
		/*if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		} else {
		echo "Connexion réussie";
		}*/
?>

		<h1>Scanner de QR code</h1>
		<div id="scanner">
			<video id="preview"></video>
			<canvas id="qr-canvas"></canvas>
		</div>
		<div>
			<button onclick="startScanner()">Démarrer le scanner</button>
		</div>
		<div id="result"></div>
		<script>
			
			function startScanner() {
				// Créer une instance du scanner
				let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
				
				
				//ajout du listener
				
				scanner.addListener('scan', function(content) {
						// Récupérer l'ID du QR code scanné
						let scannedId = "'"+content+"'";
						alert("on a scanne l'id "+scannedId);
						var request = $.ajax({
							url: "query.php", // URL du script PHP pour la requête SQL
							method: "POST",
							dataType: "json",
							data: {scannedId: scannedId}
							});
						
						
						//definition de la callback success de requete ajax query
						request.done( function(data) {
							alert('hello ajax ok !');
                                                        alert(JSON.stringify(data));
							let result = JSON.parse(JSON.stringify(data));
							alert('json parsé');
							let rjmValue = result.RJM;
							alert('rjm value ='+rjmValue);
							let vegeValue = result.vege;
							alert('vege value ='+vegeValue);
							if (rjmValue == 1) {
								// Si la valeur RJM est égale à 1
								if (vegeValue == 1) {
								// Si la valeur végé est égale à 1
								document.body.style.backgroundColor = "green";
								document.getElementById('result').textContent = "Repas validé (végé)";
								} else {
								// Si la valeur végé est égale à 0
								document.body.style.backgroundColor = "orange";
								document.getElementById('result').textContent = "Repas validé";
								}

								// Passer la valeur RJM à 2 dans la base de données
								var updateRequest = $.ajax({
									method: "POST",
									url: "update.php",
									data: {id: scannedId},
                                                                        });
								updateRequest.done(function(response) 
                                                                        {
                                                                                alert("la requete d'update a reussi");
										// Afficher la réponse de la requête d'update
										console.log(response);
									});
                                                                updateRequest.fail(function(xhr, status, error) 
                                                                         {
                                                                                alert("echec de la requete d'update");
										console.error(error);
									});

							} else if (rjmValue == 0) {
								// Si la valeur RJM est égale à 0
								document.body.style.backgroundColor = "red";
								document.getElementById('result').textContent = "Pas de repas prévu";
							} else {
								// Si la valeur RJM est égale à 2
								document.body.style.backgroundColor = "red";
								document.getElementById('result').textContent = "Repas déjà validé";
							}
						});
						
						//fin de la callback success
						
						//definition de la callback error de la requete ajax query
						request.fail( function(xhr, status, error) {
							alert("La requête s'est terminée en échec. Infos : " + JSON.stringify(error));
							console.log("Une erreur s'est produite lors de la requête Ajax: " + status + " " + error);
						}
						);
						//fin de la callback error
				
				});
				
				//fin de l'ajout du listener
				
				// Démarrer le scanner
				Instascan.Camera.getCameras().then(function(cameras) {
					if (cameras.length > 1) 
					{
						scanner.start(cameras[1]);
					} else 
					{
						scanner.start(cameras[0]);
					}
				}).catch(function (e) {
					console.error(e);
				});
				//fin du démarrage du scanner
			};
		</script>
	<?php 
}else{

     header("Location: index.php");

     exit();
}

?>
<!-- Footer -->
<?php include"footer.php"?>
</body>
</html>