<!DOCTYPE html>
<html>
    <!-- Inclusion des scripts et liens -->
    <?php include"link.php"?>
    <head>
        <link rel="stylesheet" href="style_table.css" />
    </head>
    
    <!-- Navigation -->
    <?php include"nav.php"?>
    <!-- Header -->
    <?php include"header.php"?>


<body>
<table class="my-table">
  <tr>
    <th class="centered">ID</th>
    <th class="centered">Prénon</th>
    <th class="centered">Nom</th>
    <th class="centered">Végé</th>
    <th class="centered">Jeudi Midi</th>
    <th class="centered">Vendredi Midi</th>
    <th class="centered">Vendredi Soir</th>
    <th class="centered">Samedi Midi</th>
    <th class="centered">Dimanche Midi</th>

  </tr>
</div>

<?php
// Initialiser la session
session_start();
include "config.php";
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (isset($_SESSION["username"])) {
    // Vérification de la connexion
    /*if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } else {
    echo "Connexion réussie";
    }*/

    $sql = "SELECT * FROM benevoles";
    $result = $conn->query($sql);

    // Étape 3 : Afficher les résultats
    if ($result->num_rows > 0) {
        // Parcourir les lignes de résultat
        while ($row = $result->fetch_assoc()) {
            // Afficher chaque ligne de résultat dans une nouvelle rangée du tableau
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["family_name"] . "</td>";
            echo "<td>" . ($row["vege"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RJM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RVM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RVS"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RSM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RDM"] == 1 ? "oui" : "non") . "</td>";
            echo "</tr>";
        }
        // Fermer le tableau
        echo "</>";
    } else {
        echo "0 résultats";
    }
    


    // Étape 4 : Fermer la connexion
    $conn->close();

    ?>

        <!-- Si l'utilisateur n'est pas connecté-->
        <?php
} else {

    header("Location:index.php");

    exit();
}
?>
    </body>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    
                </div>
            </div>
        </div>
    </footer>
</html>