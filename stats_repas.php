<!DOCTYPE html>
<html>
    <?php include "link.php"?>
    <?php include_once "header.php"?>
    
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div>
                        <h2>Statistiques des repas</h2>
                        <p>Statistique en temps réel des repas conssomé au festival par les bénévoles et les externes. N'apparaissent en non pris, la quantité de repas activés. </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="accordion">
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button">2024</button>
                </div>
                <div class="accordion-content">
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
                                    FROM benevoles_2024";

                            try {
                                $stmt = $pdo->query($sql);
                                
                                echo "<table>";
                                echo "<tr><th>Jour</th><th>Repas actifs</th><th>Repas consommés</th><th>Repas disponibles</th>";
                                if ($_SESSION['user']->getUserRole() === 'admin') {
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
                                            $total = 500;
                                            $mealDispo = $total - $row[$jourAbbr . '_count'];
                                            // Création des boutons dans la colonne Repas disponibles
                                            echo "<tr>";
                                            echo "<td>$jourLabel</td>";
                                            echo "<td>" . $row[$jourAbbr . '_count'] . "</td>";
                                            echo "<td>" . $row[$jourAbbr . '_count_pris'] . "</td>";
                                            echo "<td>" . $mealDispo ."</td>";

                                            if ($_SESSION['user']->getUserRole() === 'admin') {
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
                    <!-- Ajoutez votre tableau ou tout autre contenu ici -->
                    </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button">2023</button>
                </div>
                <div class="accordion-content">
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
                                    FROM benevoles_2023";

                            try {
                                $stmt = $pdo->query($sql);
                                
                                echo "<table>";
                                echo "<tr><th>Jour</th><th>Repas restant</th><th>Repas pris</th>";
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
                </div>
            </div>
        </div>
    <style>
            /* Styles CSS pour l'accordéon */
            .accordion {
                width: 100%;
            }
            .accordion-item {
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .accordion-button {
                background-color: #f0f0f0;
                color: #333;
                border: none;
                padding: 10px 20px;
                text-align: center;
                width: 100%;
                cursor: pointer;
                outline: none;
            }
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out;
                padding: 0 20px;
            }
            .accordion-item.open .accordion-content {
                max-height: 1000px; /* ou toute autre valeur appropriée */
            }
        </style>
    
        <script>
            // JavaScript pour le fonctionnement de l'accordéon
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');
                const content = item.querySelector('.accordion-content');
                
                header.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');
                    
                    accordionItems.forEach(accItem => {
                        accItem.classList.remove('open');
                        accItem.querySelector('.accordion-content').style.maxHeight = null;
                    });
                    
                    if (!isOpen) {
                        item.classList.add('open');
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });
            });
        </script>
    </body>
    <?php include "footer.php" ?>
</html>