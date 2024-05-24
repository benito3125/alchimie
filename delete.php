<?php
include "config.php";
// Vérifier si l'ID a été envoyé
if (isset($_POST['id'])) {
    // Récupérer l'ID de l'élément à supprimer
    $id = $_POST['id'];
    try {
        // Création d'une nouvelle connexion PDO
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL pour mettre à jour la base de données
        $sql = "UPDATE accessoires SET tshirt = 'N/A', tshirt_number = NULL WHERE ID = :id";

        // Préparation de la requête
        $stmt = $conn->prepare($sql);

        // Liaison de paramètres
        $stmt->bindParam(':id', $id);

        // Exécution de la requête
        $stmt->execute();

        // Réponse de succès
        echo "Élément supprimé avec succès.";
    } catch(PDOException $e) {
        // En cas d'erreur PDO, affichage du message d'erreur
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion à la base de données
    $conn = null;
} else {
    // Si l'ID n'a pas été envoyé, renvoyer un message d'erreur
    echo "Erreur : ID non spécifié.";
}
?>
