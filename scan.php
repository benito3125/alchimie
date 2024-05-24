<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instascan QR Code Scanner</title>
    <!-- Inclure la bibliothèque Instascan -->
    <script src="./js/instascan.min.js"></script>
</head>
<body>
    <h1>Instascan QR Code Scanner</h1>
    <div id="scanner-container">
        <video id="preview"></video>
    </div>

    <script>
        // Créer une instance du scanner
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        // Ajouter un écouteur pour la détection des QR codes
        scanner.addListener('scan', function(content) {
            alert('Contenu du QR code : ' + content);
        });

        // Démarrer le scanner en cherchant la première caméra disponible
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('Aucune caméra trouvée.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>
</body>
</html>
