<!DOCTYPE html>
<html>

	<!-- Inclusion des scripts et liens -->
	<?php include"link.php"?>
	<head>
		<link rel="stylesheet" href="../css/style-qrcode.css" />
	</head>
    <!-- Navigation -->
    <?php include"nav.php"?>
    <!-- Header -->
    <?php include"header.php"?>

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
		
		<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
		<script>
			function startScanner() {
				// Créer une instance du scanner
				let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
				
				// Fonction qui sera appelée lorsqu'un code QR est détecté
				scanner.addListener('scan', function(content) {
					// Afficher le contenu du code QR
					document.getElementById('result').textContent = content;
					
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
        <form action="action_page.php" method="post">
            <select id="jour">
                <option value="RJM">RJM</option>
                <option value="RVM">RVM</option>
                <option value="RVS">RVS</option>
                <option value="RSM">RSM</option>
                <option value="RDM">RDM</option>
            </select>
        </form>
        <?php
        $id = $_POST['id'];
        $jour = $_POST['jour'];
        ?>
	<?php 
}else{

     header("Location: index.php");

     exit();
}

?>
</body>
</html>
