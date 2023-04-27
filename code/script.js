scanner.addListener('scan', function(content) {
    // Récupérer l'ID du QR code scanné
    let scannedId = content;
    alert(content);
    // Vérifier si la valeur RJM est égale à 1 pour cet ID
    let query = "SELECT RJM, vege FROM benevoles WHERE ID = " + scannedId;
    let result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $rjmValue = $row["RJM"];
    $vegeValue = $row["vege"];

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
