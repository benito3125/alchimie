<?php
// Démarrer la session
header('Content-Type: text/html; charset=UTF-8');
require_once "config.php";
require_once "mysql.php";
try {
    $db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}


// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'role_') === 0) {
            $userId = substr($key, 5); // Récupérer l'ID de l'utilisateur depuis le nom du champ
            // Mettre à jour le rôle de l'utilisateur dans la base de données
            updateRole($db, $userId, $value);
        } elseif (strpos($key, 'delete_') === 0) {
            $userId = substr($key, 7); // Récupérer l'ID de l'utilisateur depuis le nom du champ
            // Supprimer l'utilisateur de la base de données
            deleteUser($db, $userId);
        } elseif (strpos($key, 'send_email_') === 0) {
            $userId = substr($key, 10); // Récupérer l'ID de l'utilisateur depuis le nom du champ
            $utilisateur = getUserById($db, $userId);
            if ($utilisateur) {
                sendEmail($utilisateur['email'], 'Sujet de l\'email', 'Bonjour, voici vos détails : Rôle : ' . $utilisateur['role'] . ', Email : ' . $utilisateur['email']);
            }
        }
    }
}

// Récupérer la liste des utilisateurs
$utilisateurs = getUsers($db);

// Séparer les utilisateurs avec le rôle "admin" et les autres utilisateurs
$admins = [];
$nonAdmins = [];

foreach ($utilisateurs as $utilisateur) {
    if ($utilisateur['role'] === 'admin') {
        $admins[] = $utilisateur;
    } else {
        $nonAdmins[] = $utilisateur;
    }
}

// Fusionner les deux listes (les admins d'abord)
$utilisateurs = array_merge($admins, $nonAdmins);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

    <title>Liste des Utilisateurs</title>
    <!-- Inclure les liens vers les scripts et les styles -->
    <?php include "link.php"?>
    <style>
        .btn-orange,
        .btn-danger {
            border: none;
            background: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Inclure l'en-tête -->
    <?php include "header.php"?>
    <div class="container">
        <h1>Liste des Utilisateurs</h1>
        <form method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Utilisateur</th>
                        <th>Rôle actuel</th>
                        <th>Nouveau rôle</th>
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <?php htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); ?>
                        <!-- Ajout des colonnes first_name et family_name -->
                        <td><?= htmlspecialchars($utilisateur['first_name']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['family_name']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                        <td>
                            <?php if ($utilisateur['role'] !== 'admin'): ?> <!-- Afficher la liste déroulante sauf pour les utilisateurs avec le rôle admin -->
                                <select name="role_<?= htmlspecialchars($utilisateur['id']) ?>">
                                    <option value="admin" <?= ($utilisateur['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="accueil" <?= ($utilisateur['role'] === 'accueil') ? 'selected' : '' ?>>Accueil</option>
                                    <option value="comite" <?= ($utilisateur['role'] === 'comite') ? 'selected' : '' ?>>Comité</option>
                                    <option value="cuisine" <?= ($utilisateur['role'] === 'cuisine') ? 'selected' : '' ?>>Cuisine</option>
                                    <option value="tombolat" <?= ($utilisateur['role'] === 'tombolat') ? 'selected' : '' ?>>Tombolat</option>
                                    <option value="baj" <?= ($utilisateur['role'] === 'baj') ? 'selected' : '' ?>>Bourse aux jeux</option>
                                    <option value="tournois" <?= ($utilisateur['role'] === 'tournois') ? 'selected' : '' ?>>Tournois</option>
                                    <option value="benevole_accueil" <?= ($utilisateur['role'] === 'benevole_accueil') ? 'selected' : '' ?>>Bénévole Accueil</option>
                                    <option value="visiteur" <?= ($utilisateur['role'] === 'visiteur') ? 'selected' : '' ?>>Visiteur</option>
                                </select>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($utilisateur['role'] !== 'admin' && $utilisateur['email'] !== $_SESSION['user']): ?> <!-- Ne pas afficher les boutons d'action pour les utilisateurs avec le rôle admin -->
                                <button type="submit" name="submit_<?= $utilisateur['id'] ?>" class="btn-orange" style="border: none;">
                                    <img src="icons/check.png" alt="Valider" style="width: 20px; height: 20px;">
                                </button>
                                <button type="submit" name="delete_<?= $utilisateur['id'] ?>" style="border: none;">
                                    <img src="icons/delete.png" alt="Supprimer" style="width: 20px; height: 20px;">
                                </button>
                                <button type="submit" name="send_email_<?= $utilisateur['id'] ?>" style="border: none;">
                                    <img src="icons/mail.png" alt="Envoyer un e-mail" style="width: 20px; height: 20px;">
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>


                </tbody>
            </table>
        </form>
    </div>
</body>
<?php include "footer.php"?>
</html>
