<?php

require_once 'mysql.php';

// Vérifier si les données sont envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données envoyées depuis JavaScript
    $categorie = $_POST['categorie'];
    $question = $_POST['question'];
    $reponse = $_POST['reponse'];

    // Insérer les données dans la table faq_data
    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête d'insertion
        $sql = "INSERT INTO faq_data (tag, query, response) VALUES (:categorie, :question, :reponse)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':reponse', $reponse);
        $stmt->execute();

        // Fermer la connexion
        $conn = null;

        // Envoyer une réponse au client
        echo "Question ajoutée avec succès.";
    } catch(PDOException $e) {
        // Gestion des erreurs
        echo "Erreur d'ajout de la question: " . $e->getMessage();
    }
}

?>
