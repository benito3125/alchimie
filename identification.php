<?php
require_once 'mysql.php'; // Assurez-vous d'inclure votre script PDO pour la connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    // Assurez-vous de récupérer le prénom et le rôle de l'utilisateur à partir de votre base de données
    $user_id = $_SESSION['user']; // Suppose que vous avez un champ 'id' dans votre session utilisateur
    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
    $stmt = $pdo->prepare("SELECT first_name, role FROM users WHERE ID = :ID");
    $stmt->bindParam(':ID', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur existe dans la base de données, affichez son prénom et son rôle
    if ($user) {
        $first_name = $user['first_name'];
        $role = $user['role'];
        echo "Bonjour $first_name, vous êtes connecté en tant que $role.";
    } else {
        echo "Erreur: Utilisateur introuvable.";
    }
} else {
    echo "Vous n'êtes pas connecté."; // Si l'utilisateur n'est pas connecté
}
?>
