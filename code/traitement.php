<?php
// Récupération des variables
session_start();
include "config.php";
// Vérification de la connexion
$id = $_GET['id'];
$jour = $_GET['jour'];
// Requête SQL pour vérifier si le bénévole a un repas prévu
$sql = "SELECT * FROM benevoles WHERE ID = '$id' AND RJM = '$jour'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mise à jour de la base de données pour indiquer que le repas a été validé
    $sql = "UPDATE benevoles SET $jour = 2 WHERE ID = '$id'";
    if ($conn->query($sql) === TRUE) {
        // Fermeture de la connexion à la base de données
        $conn->close();

        // Envoi de la réponse
        $message = "Repas validé";
    } else {
        echo "Erreur lors de la mise à jour de la base de données: " . $conn->error;
    }
} else {
    // Fermeture de la connexion à la base de données
    $conn->close();

    // Envoi de la réponse
    $message =  "Pas de repas prévu";
}
?>
