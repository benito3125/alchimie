<!DOCTYPE html>
<html>
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style_table.css" />
        <link rel="stylesheet" href="../css/style.css" />
    </head> 

    <?php include_once "header.php"?>
    
    <body>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div>
                        <h2>Statistiques des repas 2023</h2>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
        <?php        
        try {
            $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

        if (isset($_SESSION['user'])) {            

            // Tableau des jours dans l'ordre souhaité
            $jours = array(
                "RJM" => "Jeudi Midi", 
                "RVM" => "Vendredi Midi", 
                "RVS" => "Vendredi Soir", 
                "RSM" => "Samedi Midi", 
                "RDM" => "Dimanche Midi"
            );

            // Requête SQL pour obtenir les données des repas
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

            try {
                $stmt = $pdo->query($sql);
                
                echo "<table>";
                echo "<tr><th>Jour</th><th>Repas restant</th><th>Repas pris</th>";
                if ($_SESSION['user']->getUserRole() === 'comite' || $_SESSION['user']->getUserRole() === 'admin') {
                    echo "<th>Listing</th>";
                }
                echo "</tr>";

                if ($stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        foreach ($jours as $jourAbbr => $jourLabel) {
                            $linkNonPris = "liste_repas_non_pris.php?jour=$jourAbbr";
                            $linkPris = "liste_repas_pris.php?jour=$jourAbbr";
                            $labelNonPris = "Non pris";
                            $labelPris = "Pris";
                            
                            // Création des boutons dans la colonne Repas disponibles
                            echo "<tr>";
                            echo "<td>$jourLabel</td>";
                            echo "<td>" . $row[$jourAbbr . '_count'] . "</td>";
                            echo "<td>" . $row[$jourAbbr . '_count_pris'] . "</td>";
                            if ($_SESSION['user']->getUserRole() === 'comite' || $_SESSION['user']->getUserRole() === 'admin') {
                                echo "<td>";
                                echo "<a href='$linkNonPris' class='btn-sm'>$labelNonPris</a>";
                                echo "<a href='$linkPris' class='btn-sm'>$labelPris</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun résultat trouvé</td></tr>";
                }
                echo "</table>";
            } catch (PDOException $e) {
                echo "Erreur d'exécution de la requête : " . $e->getMessage();
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
