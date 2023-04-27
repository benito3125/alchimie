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
            
            <?php
            // Initialiser la session
            session_start();
            include "config.php";
            // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
            if (isset($_SESSION["username"])) {
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
                        echo "<td>" . ($row["RJM"] == 1 ? "oui" : ($row["RJM"] == 2 ? "consommé" : "non")) . "</td>";
                        echo "<td>" . ($row["RVM"] == 1 ? "oui" : ($row["RVM"] == 2 ? "consommé" : "non")) . "</td>";
                        echo "<td>" . ($row["RVS"] == 1 ? "oui" : ($row["RVS"] == 2 ? "consommé" : "non")) . "</td>";
                        echo "<td>" . ($row["RSM"] == 1 ? "oui" : ($row["RSM"] == 2 ? "consommé" : "non")) . "</td>";
                        echo "<td>" . ($row["RDM"] == 1 ? "oui" : ($row["RDM"] == 2 ? "consommé" : "non")) . "</td>";
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
    </table>
    </body>

    <!-- Footer -->
    <?php include "footer.php"?>
</html>