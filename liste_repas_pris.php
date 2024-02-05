<!DOCTYPE html>
<html>
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style_table.css" />
        <link rel="stylesheet" href="../css/style.css" />
        <style>
            .center {
                text-align: center;
            }
        </style>
    </head> 

    <?php include "header.php"?>
    <body>
        <?php

        // Vérifier si le paramètre 'jour' est défini dans l'URL
        if (isset($_GET['jour']) && in_array($_GET['jour'], ['RJM', 'RVM', 'RVS', 'RSM', 'RDM'])) {
            $jour = $_GET['jour'];

            try {
                $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Requête SQL pour obtenir la liste des utilisateurs qui ont pris leur repas
                $sql = "SELECT first_name, family_name FROM benevoles WHERE $jour = 2";
                $stmt = $pdo->query($sql);

                // Afficher la liste des utilisateurs dans un tableau
                echo "<h2 class='center'>Liste des repas pris pour le ";
                switch ($jour) {
                    case 'RJM':
                        echo "Jeudi Midi";
                        break;
                    case 'RVM':
                        echo "Vendredi Midi";
                        break;
                    case 'RVS':
                        echo "Vendredi Soir";
                        break;
                    case 'RSM':
                        echo "Samedi Midi";
                        break;
                    case 'RDM':
                        echo "Dimanche Midi";
                        break;
                }
                echo " :</h2>";

                echo "<table>";
                echo "<tr><th>Prénom</th><th>Nom</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>" . $row['first_name'] . "</td><td>" . $row['family_name'] . "</td></tr>";
                }
                echo "</table>";

                $pdo = null; // Fermeture de la connexion à la base de données
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "Jour invalide.";
        }
        ?>
    </body>
    <?php include "footer.php" ?>
</html>
