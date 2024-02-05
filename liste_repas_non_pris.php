<!DOCTYPE html>
<html>
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style_table.css" />
        <link rel="stylesheet" href="../css/style.css" />
    </head> 

    <?php include "header.php"?>
    <body>
        <?php
        // Inclure le fichier de configuration et établir la connexion à la base de données
        //include "config.php";

        // Vérifier si le paramètre 'jour' est défini dans l'URL
        if (isset($_GET['jour']) && in_array($_GET['jour'], ['RJM', 'RVM', 'RVS', 'RSM', 'RDM'])) {
            $jour = $_GET['jour'];

            try {
                $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Requête SQL pour obtenir la liste des utilisateurs qui n'ont pas pris leur repas
                $sql = "SELECT first_name, family_name FROM benevoles WHERE $jour = 1";
                $stmt = $pdo->query($sql);

                // Afficher la liste des utilisateurs
                echo "<h2>Liste des repas non pris pour le jour $jour :</h2>";
                echo "<ul>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>" . $row['first_name'] . " - " . $row['family_name'] . "</li>";
                }
                echo "</ul>";

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
