<!DOCTYPE html>
<html>

    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style_table.css" />
        <link rel="stylesheet" href="../css/style.css" />
    </head>
    
    <!-- Navigation -->
    <?php include "nav.php"?>

    <!-- Header -->
    <?php include "header.php"?>
    <body>
    <div class="button-add-container">
        <a href="stats.php" class="button-add">Retour</a>
        <a href="down_rjm.php" class="button-add">Télécharger la liste</a>
    </div>
<?php
include "config.php";
// Initialiser la session
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (isset($_SESSION["username"])) {
    $sql = "SELECT * FROM benevoles WHERE RJM = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Afficher les résultats sous forme de tableau
        echo "<table>";
        echo "<tr><th>Prénom</th><th>Nom</th><th>Végétarien</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["family_name"] . "</td>";
            echo "<td>" . ($row["vege"] == 1 ? "oui" : "non") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Aucun résultat trouvé.";
    }
    $conn->close();

} else {
    header("Location: index.php");
    exit();
}
?>
   </body>

<!-- Footer -->
<?php include "footer.php"?>
</html>