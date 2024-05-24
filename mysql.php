<?php
require_once "config.php";

function connectToDatabase() {
    // Créez une connexion
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    return $conn;
}

// Fonction pour enregistrer un nouvel utilisateur
function register ( $email, $password, $first_name, $family_name) {
    if (userExist($email)){
        return [null, "Un compte existe déjà à cette adresse email."];
    }

    $hash = password_hash ($password, PASSWORD_DEFAULT);
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $query="INSERT INTO users (email, password, first_name, family_name) VALUES (:email,:password,:first_name,:family_name)";
        $stmt=$sql->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':password',$hash);
        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':family_name',$family_name);
        $stmt->execute();
        $dr=$stmt->fetch();
    return [$sql -> lastInsertId (), NULL];
}

// Fonction pour voir si l'utilisateur existe lors de la création de son compte
function userExist ($email){
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $query="SELECT email FROM users WHERE email=:email";
        $stmt=$sql->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $dr=$stmt->fetch();
    return (isset($dr) && !empty($dr));
}

// Fonction pour se connecter au site
function login ($email, $password ){
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $query="SELECT ID, password FROM users WHERE email=:email";
        $stmt=$sql->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $dr=$stmt->fetch();
    if (password_verify($password, $dr['password'])){
        return $dr['ID'];
    }
    return NULL;
}

// Fonction pour récupérer l'ID de l'utilisateur afin après de connaitre son rôle
function getUserId($email){
    $sql = "SELECT * FROM users WHERE email='$email'";
        $connection = connectToDatabase();
        $result = mysqli_query($connection, $sql);

        // Check if the query was successful
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Process each row
                return $row["ID"];
            }
        } else {
            return -1;
        }
}

// Fonction pour récupérer le prénom et nom de l'utilisateur
function getUserNameById($userid){
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $query = "SELECT first_name FROM users WHERE ID = ?";
    $connection = connectToDatabase();
    $stmt = $sql->prepare($query);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["first_name"];
        }
    }else{
        return null;
    }
}

// Fonction pour récupérer le rôle de l'utilisateur et lui donner les bons accès
function getUserRole($userid){
    $connection = connectToDatabase();
    $query = "SELECT role FROM users WHERE ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["role"];
        }
    }else{
        return null;
    }
}

// Fonction pour récupérer les données de la FAQ depuis la base de données
function getFAQData() {
    // Informations de connexion à la base de données
    try {
        // Connexion à la base de données avec PDO et encodage UTF-8
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

        // Configuration de PDO pour afficher les erreurs
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL pour récupérer les données de la FAQ
        $sql = "SELECT tag, query, response, file FROM faq_data";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Initialisation du tableau pour stocker les données de la FAQ regroupées par tag
        $faqData = array();

        // Récupération des résultats sous forme de tableau associatif
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Ajout des questions et réponses à la clé du tableau correspondant au tag
            $faqData[$row['tag']][] = array(
                'query' => $row['query'],
                'response' => $row['response'],
                'file' => $row['file']
            );
        }

        // Fermeture de la connexion à la base de données
        $conn = null;

        // Retourner les données de la FAQ
        return $faqData;
    } catch(PDOException $e) {
        // Gestion des erreurs de connexion à la base de données
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
    }
}

// Fonction pour récupérer l'ensemble des informations concernant l'utilisateur
function getUsers(PDO $db) {
    try {
        $query = "SELECT id, email, role, first_name, family_name FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        return [];
    }
}

// Fonction pour mettre à jour le role de l'utilisateur
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

// Fonction pour supprimer l'ensemble des données de l'utilisateur dans la base de données
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

// Fonction pour récupérer l'emaile et le role de l'utilisateur
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

// Fonction pour envoyer un email à l'utilisateur
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

// Fonction pour récupérer les données utilisées pour l'affichage du graph
function getData($sql, $params = []) {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    } finally {
        // Fermer la connexion à la base de données
        $conn = null;
    }
}

// Fonction pour insérer les données saisies dans la table visiteurs_2024
function insertDataTo2024($nombre_handi, $nombre_lsf, $nombre_autres, $nombre_total, $heure, $jour) {
    try {
        // Chaîne de connexion PDO
        $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

        // Options PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        // Préparer et exécuter la requête d'insertion
        $sql = "INSERT INTO visiteurs_2024 (nombre_handi, nombre_lsf, nombre_autres, nombre_total, heure, jour) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_handi, $nombre_lsf, $nombre_autres, $nombre_total, $heure, $jour]);
        
        // Retourner vrai si l'insertion réussit
        return true;
    } catch(PDOException $e) {
        // Afficher l'erreur en cas d'échec
        echo "Error: " . $e->getMessage();
        // Retourner faux en cas d'échec
        return false;
    }
}

?>