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
        <?php
        // Initialiser la session
        include "config.php";
        // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
        if (isset($_SESSION['user'])) {            
            // Requête SQL pour obtenir le COUNT pour chaque colonne RJM à RDM
            $sql = "SELECT COUNT(CASE WHEN RJM = 1 THEN 1 END) AS RJM_count,
                    COUNT(CASE WHEN RJM = 2 THEN 2 END) AS RJM_count_pris,
                    COUNT(CASE WHEN RVM = 1 THEN 1 END) AS RVM_count,
                    COUNT(CASE WHEN RVM = 2 THEN 2 END) AS RVM_count_pris,
                    COUNT(CASE WHEN RVS = 1 THEN 1 END) AS RVS_count,
                    COUNT(CASE WHEN RVS = 2 THEN 2 END) AS RVS_count_pris,
                    COUNT(CASE WHEN RSM = 1 THEN 1 END) AS RSM_count,
                    COUNT(CASE WHEN RSM = 2 THEN 2 END) AS RSM_count_pris,
                    COUNT(CASE WHEN RDM = 1 THEN 1 END) AS RDM_count,
                    COUNT(CASE WHEN RDM = 2 THEN 2 END) AS RDM_count_pris
                    FROM benevoles";

            $result = $conn->query($sql);

            // Création du tableau
            echo "<table>";
            echo "<tr><th>Jour</th><th>Nombre de repas restant</th><th>Nombre de repas pris</th><th>Repas disponibles</th></tr>";

            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>Jeudi Midi</td><td>" . $row['RJM_count'] . "</td><td>" . $row['RJM_count_pris'] . "</td><td><a href='listrjm.php' class='btn'>Listing</a></td></tr>";
                echo "<tr><td>Vendredi Midi</td><td>" . $row['RVM_count'] . "</td><td>" . $row['RVM_count_pris'] . "</td><td><a href='listrvm.php' class='btn'>Listing</a></td></tr>";
                echo "<tr><td>Vendredi Soir</td><td>" . $row['RVS_count'] . "</td><td>" . $row['RVS_count_pris'] . "</td><td><a href='listrvs.php' class='btn'>Listing</a></td></tr>";
                echo "<tr><td>Samedi Midi</td><td>" . $row['RSM_count'] . "</td><td>" . $row['RSM_count_pris'] . "</td><td><a href='listrsm.php' class='btn'>Listing</a></td></tr>";
                echo "<tr><td>Dimanche Midi</td><td>" . $row['RDM_count'] . "</td><td>" . $row['RDM_count_pris'] . "</td><td><a href='listrdm.php' class='btn'>Listing</a></td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Aucun résultat trouvé</td></tr>";
        }
        echo "</table>";
        // Fermeture de la connexion à la base de données
        $conn->close();
        ?>

        <!-- Si l'utilisateur n'est pas connecté-->
        <?php 
        }else{
            header("Location: index.php");
            exit();
        }
        ?>
    </body>
<!-- Footer -->
<?php include "footer.php" ?>


</html>
