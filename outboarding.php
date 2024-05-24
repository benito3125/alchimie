<?php
// Inclusion du fichier de configuration et des fonctions d'accès à la base de données
require_once "config.php";
require_once "mysql.php";

// Vérifie si un ID a été envoyé pour la suppression
if (isset($_POST['id'])) {
    // Connexion à la base de données
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête de suppression
        $sql = "UPDATE accessoires SET tshirt = 'N/A', tshirt_number = NULL WHERE CONCAT(tshirt, ' ', tshirt_number) = :tshirt_number";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':tshirt_number', $_POST['id']);
        
        // Exécution de la requête
        $stmt->execute();

        echo "Élément supprimé avec succès!";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion à la base de données
    $conn = null;
    exit; // Arrêter l'exécution du script après la suppression
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Outboarding</title>
    <!-- Inclure les liens vers les scripts et les styles -->
    <?php include "link.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .block {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }
        .delete-link {
            color: red;
            cursor: pointer;
        }
        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include "header.php"?>
    <table>
        <tr>
            <td rowspan="2" class="block">
                <h3>T-shirts distribués</h3>
                <?php
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour sélectionner les numéros de t-shirts des bénévoles
                        $sql = "SELECT CONCAT(tshirt, ' ', tshirt_number) AS tshirt_number FROM accessoires WHERE tshirt IS NOT NULL AND tshirt_number IS NOT NULL";

                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Vérification s'il y a des résultats
                        $result = $stmt->fetchAll();
                        if (count($result) > 0) {
                            echo "<ul>";
                            foreach ($result as $row) {
                                echo "<li><a href='#' class='delete-link' data-tshirt='" . $row["tshirt_number"] . "'>Supprimer</a> " . $row["tshirt_number"] . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "Aucun numéro de t-shirt trouvé.";
                        }
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur de connexion à la base de données : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
            <td class="block">
                <h3>Tour de cou distribués</h3>
                <?php
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir le nombre total de neck_warmers avec '1' et '2' séparés
                        $sql = "SELECT neck_warme, COUNT(neck_warme) AS total_neck_warmers FROM accessoires WHERE neck_warme IN ('1', '2') GROUP BY neck_warme";

                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Récupération des résultats
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Affichage des résultats
                        foreach ($results as $row) {
                            // Détermination de la valeur de neck_warme
                            if ($row['neck_warme'] === '1') {
                                $neck_warm_value = 'Violet';
                            } elseif ($row['neck_warme'] === '2') {
                                $neck_warm_value = 'Bleu';
                            }
                            // Affichage des résultats
                            echo "Tour de cou <strong>" . $neck_warm_value . "</strong> : " . $row['total_neck_warmers'] . "<br>";
                        }
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
            <td class="block">
                <h3>Serres-Têtes distribués</h3>
                <?php
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir le nombre total de neck_warmers avec '1' et '2' séparés
                        $sql = "SELECT head, COUNT(head) AS total_heads FROM accessoires WHERE head IN ('1', '2') GROUP BY head";

                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Récupération des résultats
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Affichage des résultats
                        foreach ($results as $row) {
                            // Détermination de la valeur de neck_warme
                            if ($row['head'] === '1') {
                                $head = 'Bleu';
                            } elseif ($row['head'] === '2') {
                                $head = 'Orange';
                            }
                            elseif ($row['head'] === '3') {
                                $head = 'Jaune';
                            }
                            // Affichage des résultats
                            echo "Serres-têtes <strong>" . $head . "</strong> : " . $row['total_heads'] . "<br>";
                        }
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
            <td rowspan="2" class="block">
                <h3>Liste des bénévoles n'ayant pas rendu leur t-shirt</h3>
                <?php
                    // Nouvelle requête SQL
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir les résultats supplémentaires
                        $sql = "SELECT b.family_name, b.first_name, b.phone, a.tshirt, a.tshirt_number
                        FROM benevoles b
                        JOIN accessoires a ON b.ID = a.ID_user
                        WHERE a.tshirt_number IS NOT NULL
                        ORDER BY b.family_name";

                        // Compter le nombre total de tshirt_number non vides
                        $sql_count = "SELECT COUNT(*) AS total FROM accessoires WHERE tshirt_number IS NOT NULL";
                        $stmt_count = $conn->prepare($sql_count);
                        $stmt_count->execute();
                        $count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
                        $total_tshirt_numbers = $count_result['total'];

                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Récupération des résultats
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "Il reste encore   ". $total_tshirt_numbers . "   thsirts à récupérer"."<br>";
                        echo "<br>";
                        
                        // Affichage des résultats
                        foreach ($results as $row) {
                            // Afficher les résultats de la nouvelle requête ici
                            echo $row['first_name'] . "      " . $row['family_name'] . ":   " .  $row['tshirt'] . "   -   " .  $row['tshirt_number'] ."<br>";

                        }
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
        </tr>
        <tr>
            <td class="block">
                <h3>Bracelets distribués</h3>
                <?php
                    // Nouvelle requête SQL
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir les résultats supplémentaires
                        $stock_bracelets = 800;
                        $sql = "SELECT COUNT(bracelet) AS rsbracelet FROM accessoires";
                        $stmt_count = $conn->prepare($sql);
                        $stmt_count->execute();
                        $count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
                        $total_bracelets = $count_result['rsbracelet'];
                        $total_rest_bracelets = $stock_bracelets - $total_bracelets;
                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Récupération des résultats
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "Il reste  ". $total_rest_bracelets . "  bracelets en stock."."<br>";
                        echo "<br>";
                            
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
            <td class="block">
                <h3>Porte badges</h3>
                <?php
                    // Nouvelle requête SQL
                    try {
                        // Création d'une nouvelle connexion PDO
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir les résultats supplémentaires
                        $stock_badges = 1100;
                        $sql = "SELECT COUNT(ID) AS badges FROM accessoires";
                        $stmt_count = $conn->prepare($sql);
                        $stmt_count->execute();
                        $count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
                        $total_badges = $count_result['badges'];
                        $total_rest_badges = $stock_badges - $total_badges;

                        // Préparation de la requête
                        $stmt = $conn->prepare($sql);

                        // Exécution de la requête
                        $stmt->execute();

                        // Récupération des résultats
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "Il reste  ". $total_rest_badges . "  badges en stock."."<br>";
                        echo "Nous devons récupérer   " . $total_badges . "   badges à la fin du festival."."<br>";
                        echo "<br>";
                            
                    } catch(PDOException $e) {
                        // En cas d'erreur PDO, affichage du message d'erreur
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermeture de la connexion à la base de données
                    $conn = null;
                ?>
            </td>
        </tr>
    </table>
    <!-- Votre code JavaScript existant -->

    <!-- Script de suppression -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélection de tous les liens de suppression
            var deleteLinks = document.querySelectorAll('.delete-link');
            
            // Boucle à travers chaque lien de suppression
            deleteLinks.forEach(function(link) {
                // Ajout d'un écouteur d'événements au clic
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Empêche le lien de suivre son URL
                    
                    // Récupération de l'identifiant du t-shirt à supprimer
                    var tshirtNumber = this.getAttribute('data-tshirt');
                    
                    // Confirmation de la suppression
                    var confirmation = confirm("Êtes-vous sûr de vouloir supprimer le t-shirt " + tshirtNumber + " ?");
                    
                    // Si l'utilisateur confirme la suppression
                    if (confirmation) {
                        // Envoi de la demande de suppression via AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '<?= $_SERVER['PHP_SELF'] ?>', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 400) {
                                // Rafraîchissement de la page après la suppression
                                location.reload();
                            } else {
                                console.error(xhr.responseText);
                            }
                        };
                        xhr.onerror = function() {
                            console.error(xhr.responseText);
                        };
                        xhr.send('id=' + encodeURIComponent(tshirtNumber));
                    }
                });
            });
        });
    </script>
</body>
<?php include "footer.php"?>
</html>
