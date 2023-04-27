<!DOCTYPE html>
<html>

	<!-- Inclusion des scripts et liens -->
	<?php include "link.php"?>
	<head>
		<link rel="stylesheet" href="../css/style-qrcode.css" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    	<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
		<script src="instascan.min.js"></script>
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
				
				scanner.addListener('scan', function(content) {
					// Récupérer l'ID du QR code scanné
					let scannedId = "'"+content+"'";
					//alert(scannedId);
					// Vérifier si la valeur RJM est égale à 1 pour cet ID
					//let query = "SELECT RJM, vege FROM benevoles WHERE ID = " + scannedId;	

					$.ajax({
						url: 'query.php', // URL du script PHP pour la requête SQL
						type: 'POST',
						dataType: 'json',
						data: {id: scannedId},
						success: function(data) {
							alert('hello');
							let result = JSON.parse(response.result);
							let rjmValue = result.rjm;
							let vegeValue = result.vege;
							//alert(rjmValue);
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
								$.ajax({
									type: "POST",
									url: "update.php",
									data: { "id": scannedId },
									success: function(response) {
										// Afficher la réponse de la requête d'update
										console.log(response);
									},
									error: function(xhr, status, error) {
										console.error(error);
									}
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
						}
						/*error: function(xhr, status, error) {
							console.log("Une erreur s'est produite lors de la requête Ajax: " + status + " " + error);
						}*/
					});
				});
				
				// Démarrer le scanner
				Instascan.Camera.getCameras().then(function(cameras) {
					if (cameras.length > 1) {
					scanner.start(cameras[1]);
				} else {
					scanner.start(cameras[0]);
				}
				

				}).catch(function (e) {
					console.error(e);
				});
			}
		</script>
	<?php 
}else{

     header("Location: index.php");

     exit();
}

?>
</body>
</html>
