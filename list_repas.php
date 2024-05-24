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
        try {
            $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

        if (isset($_SESSION['user'])) {            
            if (isset($_GET['jour']) && in_array($_GET['jour'], ['RJM', 'RVM', 'RVS', 'RSM', 'RDM'])) {
                $jour = $_GET['jour'];
                
                $sql = "SELECT first_name, family_name FROM benevoles WHERE $jour = 2";
                
                try {
                    $stmt = $pdo->query($sql);
                    
                    echo "<table>";
                    echo "<tr><th>Utilisateur</th><th>Nom de famille</th></tr>";

                    if ($stmt->rowCount() > 0) {
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr><td>" . $row['first_name'] . "</td><td>" . $row['family_name'] . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Aucun utilisateur n'a pris de repas pour ce jour.</td></tr>";
                    }
                    echo "</table>";

                    // Boutons pour les listes des repas pris et non pris
                    echo "<a href='liste_repas_non_pris.php?jour=$jour' class='btn'>Liste des repas non pris</a>";
                    echo "<a href='liste_repas_pris.php?jour=$jour' class='btn'>Liste des repas pris</a>";
                } catch (PDOException $e) {
                    echo "Erreur d'exécution de la requête : " . $e->getMessage();
                }
            } else {
                echo "Jour invalide.";
            }
            
            $pdo = null; // Fermeture de la connexion à la base de données
        } else {
            header("Location: index.php");
            exit();
        }
        ?>
    </body>
    <?php include "footer.php" ?>
</html>
