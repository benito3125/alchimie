<!DOCTYPE html>
<html>

	<!-- Inclusion des scripts et liens -->
	<?php include "link.php"?>
	<head>
		<link rel="stylesheet" href="../css/style-qrcode.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
		<script src="script.js"></script>
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
					// Vérifier si la valeur RJM est égale à 1 pour cet ID
					let query = "SELECT RJM, vege FROM benevoles WHERE ID = " + scannedId;
					alert(query);
					let result = mysqli_query($conn, $query);
					alert(conn);
					$row = mysqli_fetch_assoc($result);
					$rjmValue = $row["RJM"];
					alert(rjmValue);
					$vegeValue = $row["vege"];
					alert(vegeValue);
					if ($rjmValue == 1) {
						// Si la valeur RJM est égale à 1
						if ($vegeValue == 1) {
							// Si la valeur végé est égale à 1
							document.body.style.backgroundColor = "green";
							document.getElementById('result').textContent = "Repas validé (végé)";
						} else {
							// Si la valeur végé est égale à 0
							document.body.style.backgroundColor = "orange";
							document.getElementById('result').textContent = "Repas validé";
						}

						// Passer la valeur RJM à 2 dans la base de données
						let updateQuery = "UPDATE benevoles SET RJM = 2 WHERE id = " + scannedId;
						mysqli_query($conn, $updateQuery);
					} else if ($rjmValue == 0) {
						// Si la valeur RJM est égale à 0
						document.body.style.backgroundColor = "red";
						document.getElementById('result').textContent = "Pas de repas prévu";
					} else {
						// Si la valeur RJM est égale à 2
						document.body.style.backgroundColor = "red";
						document.getElementById('result').textContent = "Repas déjà validé";
					}

					// Vibration
					navigator.vibrate(100);

					// Arrêter le scanner
					scanner.stop();

					// Cacher la vidéo
					document.getElementById('scanner').style.display = 'none';
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
