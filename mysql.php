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

function register ( $email, $password ) {
    if (userExist($email)){
        return [null, "Un compte existe déjà à cette adresse email."];
    }

    $hash = password_hash ($password, PASSWORD_DEFAULT);
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $query="INSERT INTO users (email, password) VALUES (:email,:password)";
        $stmt=$sql->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':password',$hash);
        $stmt->execute();
        $dr=$stmt->fetch();
    return [$sql -> lastInsertId (), NULL];
}

function userExist ($email){
    $sql=new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $query="SELECT email FROM users WHERE email=:email";
        $stmt=$sql->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $dr=$stmt->fetch();
    return (isset($dr) && !empty($dr));
}

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



?>