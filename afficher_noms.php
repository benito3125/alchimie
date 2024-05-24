<?php
// Initialiser la session
session_start();
include "config.php";
// Récupération de la valeur du jour sélectionné

if (isset($_SESSION["username"])) {


    // Vérification de la connexion
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } else {
    echo "Connexion réussie";
    }
$day = $_POST['day'];

// Exécution de la requête SQL en utilisant la colonne correspondante
$sql = "SELECT first_name, family_name, $column FROM benevoles";
$result = $conn->query($sql);

// Afficher les résultats dans un tableau
if ($result->num_rows > 0) {
  echo "<table>";
  echo "<tr><th>First Name</th><th>Family Name</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["first_name"] . "</td><td>" . $row["family_name"] . "</td></tr>";
  }
  echo "</table>";
} else {
  echo "0 résultats";
}
// Sélection de la colonne correspondante en fonction du jour
switch($day) {
    case 'RJM':
        $column = 'RJM';
        break;
    case 'RVM':
        $column = 'RVM';
        break;
    case 'RVS':
        $column = 'RVS';
        break;
    case 'RSM':
        $column = 'RSM';
        break;
    case 'RDM':
        $column = 'RDM';
        break;
    default:
        $column = '';
}

//Si l'utilisateur n'est pas connecté
} else {

header("Location:index.php");

exit();
}
?>
