<?php// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
echo('toto');
if (isset($_SESSION["username"])) {
    echo('username');
    if( isset( $_POST['id'] ) ){
        // Récupération de l'ID depuis la requête Ajax
        $id = $_POST['id'];
        echo($id);
        include "config.php";
        // Préparation de la requête SQL
        $sql = "SELECT RJM, vege FROM benevoles WHERE ID = '$id'";
        echo($sql);
        // Exécution de la requête SQL
        $result = $conn->query($sql);
        // Création d'un tableau pour stocker les résultats
        $rows = array();
        // Boucle à travers les résultats et stockage dans le tableau
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        // Conversion du tableau en format JSON et envoi de la réponse
        header('Content-Type: application/json');
        echo json_encode($rows);
    } else {
        // envoi d'un message d'erreur en format JSON si aucun produit n'a été trouvé avec l'id du QR code scanné
        $error = array('message' => 'Product not found');
        echo json_encode($error);
    }
    // fermeture de la connexion à la base de données
    mysqli_close($conn);
} else {
    // envoi d'un message d'erreur en format JSON si l'id du QR code n'a pas été transmis
    $error = array('message' => 'No QR code ID provided');
    echo json_encode($error);

//Si l'utilisateur n'est pas connecté
}else{
    header("Location: login.php");
    exit();
}
?>