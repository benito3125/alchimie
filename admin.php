<?php
// Démarrer la session
require_once "config.php";

function getUsers(PDO $db) {
    try {
        $query = "SELECT id, email, role FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        return [];
    }
}

function updateRole(PDO $db, $userId, $newRole) {
    try {
        $updateQuery = "UPDATE users SET role = :role WHERE id = :id";
        $updateStatement = $db->prepare($updateQuery);
        $updateStatement->bindParam(':role', $newRole);
        $updateStatement->bindParam(':id', $userId);
        $updateStatement->execute();
        return true; // Succès de la mise à jour
    } catch (PDOException $ex) {
        // En cas d'erreur, affichez le message d'erreur
        echo "Erreur lors de la mise à jour de l'utilisateur avec l'ID $userId : " . $ex->getMessage();
        return false; // Échec de la mise à jour
    }
}

function deleteUser(PDO $db, $userId) {
    try {
        $deleteQuery = "DELETE FROM users WHERE id = :id";
        $deleteStatement = $db->prepare($deleteQuery);
        $deleteStatement->bindParam(':id', $userId);
        $deleteStatement->execute();
        return true; // Succès de la suppression
    } catch (PDOException $ex) {
        // En cas d'erreur, affichez le message d'erreur
        echo "Erreur lors de la suppression de l'utilisateur avec l'ID $userId : " . $ex->getMessage();
        return false; // Échec de la suppression
    }
}

try {
    $db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Stocker l'email de l'utilisateur connecté dans la session
$_SESSION['user_email'] = "user@example.com";

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

function getUserById(PDO $db, $userId) {
    try {
        $query = "SELECT id, email, role FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(':id', $userId);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
        return null;
    }
}

function sendEmail($email, $subject, $body) {
    // Envoi d'email simplifié
    $headers = "From: detelin.bouteill@gmail.com" . "\r\n" .
               "Reply-To: your_email@example.com" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    if (mail($email, $subject, $body, $headers)) {
        echo "L'e-mail a été envoyé avec succès à $email";
    } else {
        echo "Erreur lors de l'envoi de l'e-mail à $email";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
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
                        <th>Utilisateur</th>
                        <th>Rôle actuel</th>
                        <th>Nouveau rôle</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                            <td>
                                <select name="role_<?= htmlspecialchars($utilisateur['id']) ?>">
                                    <option value="admin" <?= ($utilisateur['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="accueil" <?= ($utilisateur['role'] === 'accueil') ? 'selected' : '' ?>>Accueil</option>
                                    <option value="comite" <?= ($utilisateur['role'] === 'comite') ? 'selected' : '' ?>>Comité</option>
                                    <option value="cuisine" <?= ($utilisateur['role'] === 'cuisine') ? 'selected' : '' ?>>Cuisine</option>
                                    <option value="tombolat" <?= ($utilisateur['role'] === 'tombolat') ? 'selected' : '' ?>>Tombolat</option>
                                </select>
                            </td>
                            <td>
                                <?php if ($utilisateur['email'] !== $_SESSION['user']): ?>
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
