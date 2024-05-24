<?php

include('config.php');

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Étape 2 : Récupérer les données
$sql = "SELECT * FROM myTable";
$result = $conn->query($sql);

// Tableau des colonnes à traiter
$columns = array("col4", "col5", "col6", "col7", "col8", "col9");

// Tableau des résultats
$results = array();

// Boucle sur les colonnes
foreach($columns as $col) {
    $sum = 0;
    // Boucle sur les lignes
    while($row = $result->fetch_assoc()) {
        // Ajout de 1 si la valeur est égale à 1
        if($row[$col] == 1) {
            $sum++;
        }
    }
    // Ajout du résultat dans le tableau des résultats
    $results[$col] = $sum;
    // Remise du pointeur à la première ligne
    $result->data_seek(0);
}

// Étape 3 : Afficher les résultats
echo "<h2>Total des oui pour chaque colonne :</h2>";
echo "<table>";
echo "<tr><th>Colonne</th><th>Total</th></tr>";
foreach($results as $col => $total) {
    echo "<tr><td>".$col."</td><td>".$total."</td></tr>";
}
echo "</table>";
?>