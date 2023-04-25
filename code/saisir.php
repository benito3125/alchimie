<!DOCTYPE html>
<html>
<head>
	<title>Scanner de QR code</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
			text-align: center;
			padding-top: 50px;
		}
		h1 {
			color: #333;
			margin-bottom: 30px;
		}
		#scanner {
			margin: auto;
			width: 200px;
			height: 200px;
			background-color: #fff;
			position: relative;
			overflow: hidden;
			border: 3px solid #333;
			border-radius: 10px;
			margin-bottom: 30px;
		}
		#scanner video {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#scanner canvas {
			display: none;
		}
		#result {
			font-size: 24px;
			color: #333;
			margin-bottom: 50px;
		}
	</style>
</head>
<body>
	<?php include "config.php";?>
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
</body>
</html>
