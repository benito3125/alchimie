<?php
require_once 'mysql.php';
header('Content-Type: text/html; charset=UTF-8');

// Fonction pour afficher les repas
function afficherRepas($benevole, $pdo)
{
    echo '<h3>Repas du bénévole</h3>';
    echo '<form method="post" action="">';
    echo '<input type="hidden" name="benevole_id" value="' . $benevole['ID'] . '">';

    // Liste des repas
    $repas = array(
        'RJM' => 'Repas du Jeudi midi',
        'RVM' => 'Repas du Vendredi midi',
        'RVS' => 'Repas du Vendredi soir',
        'RSM' => 'Repas du Samedi midi',
        'RDM' => 'Repas du Dimanche midi'
    );

    foreach ($repas as $key => $label) {
        echo '<p>' . $label . ': ';
        if ($benevole[$key] == 0) {
            echo 'Non prévu   ';
            echo '<button type="submit" name="ajouter_' . $key . '">Ajouter</button>';
        } elseif ($benevole[$key] == 1) {
            echo 'Prévu   ';
            echo '<button type="submit" name="supprimer_' . $key . '">Supprimer</button>';
        } else {
            echo 'Déjà consommé';
        }
        echo '</p>';
    }

    echo '</form>';
}

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <title>Recherche de bénévole</title>
    <style>
        .affiche {
            max-width: 800px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0 12px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: orange;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #d58512;
        }

        .profile {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile h3 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

         /* Styles pour les colonnes invisibles */
         .invisible-column {
            border: 1px solid transparent; /* Définir la bordure comme transparente */
        }

        .profile p {
            margin-bottom: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <?php include "header.php"?>

    <div class="affiche">
        <h2>Recherche de bénévole par nom et prénom</h2>
        <!-- Utilisation de la méthode POST -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="family_name">Nom de famille :</label><br>
            <input type="text" id="family_name" name="family_name"><br>
            <label for="first_name">Prénom :</label><br>
            <input type="text" id="first_name" name="first_name"><br><br>
            <input type="submit" name="search" value="Chercher">
        </form>

        <?php
        // Traitement du formulaire de recherche
        if (isset($_POST['search'])) {
            if (isset($_POST['family_name']) && isset($_POST['first_name'])) {
                $family_name = $_POST['family_name'];
                $first_name = $_POST['first_name'];
                try {
                    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Requête SQL pour récupérer les informations du bénévole
                    $requete_benevole = $pdo->prepare("SELECT * FROM benevoles WHERE family_name = :family_name AND first_name = :first_name");
                    $requete_benevole->bindParam(':family_name', $family_name, PDO::PARAM_STR);
                    $requete_benevole->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                    $requete_benevole->execute();
                    $benevole = $requete_benevole->fetch(PDO::FETCH_ASSOC);
                    $benevole_id = null; // Initialisation de l'ID du bénévole

                    if ($benevole) {
                        // Affichage des informations du bénévole dans des colonnes invisibles
                        echo '<div class="invisible-column">';
                        echo '<span class="hidden-mail"><p>QR-Code:  ' . $benevole['ID'] .'</p>';
                        echo '<p>Prénom:  ' . $benevole['first_name'] .'</p>';
                        echo '<p>Nom:  '. $benevole['family_name'] . '</p></span>';
                        echo '<span class="hidden-phone"><p>Adresse mail:  ' . $benevole['mail'] .'</p>';
                        echo '<p>Numéro de téléphone:  '. $benevole['phone'] . '</p></span>';
                        // Ajoutez d'autres informations ici si nécessaire
                        echo '</div>';

                        // Appel de la fonction pour afficher les repas
                        afficherRepas($benevole, $pdo);

                        // Requête SQL pour récupérer les informations des accessoires distribués au bénévole
                        $requete_accessoires = $pdo->prepare("SELECT * FROM accessoires WHERE ID_user = :benevole_id");
                        $requete_accessoires->bindParam(':benevole_id', $benevole['ID'], PDO::PARAM_STR);
                        $requete_accessoires->execute();
                        $accessoires = $requete_accessoires->fetchAll(PDO::FETCH_ASSOC);

                        // Affichage des accessoires distribués au bénévole
                        if ($accessoires) {
                            echo '<h3>Accessoires distribués au bénévole</h3>';
                            echo '<div class="invisible-column">';
                            foreach ($accessoires as $accessoire) {
                                if ($accessoire['tshirt'] == 'N/A'){
                                    echo '<p>T-Shirt : externe </p>';
                                } elseif ($accessoire['tshirt'] !== 'N/A'){
                                    echo '<p>T-Shirt : ' . $accessoire['tshirt'] . $accessoire['tshirt_number'] . '</p>';
                                }
                                if ($accessoire['head'] == 1) {
                                    echo '<p>Serre tête: Jaune (responsable) </p>';
                                } elseif ($accessoire['head'] == 2) {
                                    echo '<p>Serre tête: Orange (bénévole) </p>';
                                } else {
                                    $head = 'N/A';
                                }
                                if ($accessoire['neck_warme'] == 1) {
                                    echo '<p>Serre tête: Violet (comité) </p>';
                                } elseif ($accessoire['head'] == 2) {
                                    echo '<p>Serre tête: Bleue (responsable) </p>';
                                } else {
                                    $head = 'N/A';
                                }
                                echo '<p>Bracelet: ' . $accessoire['bracelet'] . '</p>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p>Aucun accessoire distribué à ce bénévole.</p>';
                        }

                    } else {
                        echo "<p class='error'>Aucun bénévole trouvé avec ce nom et prénom.</p>";
                    }

                } catch(PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
        }

        // Traitement des modifications de repas
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            foreach ($repas as $key => $label) {
                if (isset($_POST['ajouter_' . $key])) {
                    $requete = $pdo->prepare("UPDATE benevoles_2024 SET $key = 1 WHERE ID = :benevole_id");
                    $requete->bindParam(':benevole_id', $_POST['benevole_id'], PDO::PARAM_STR);
                    $requete->execute();
                } elseif (isset($_POST['supprimer_' . $key])) {
                    $requete = $pdo->prepare("UPDATE benevoles_2024 SET $key = 0 WHERE ID = :benevole_id");
                    $requete->bindParam(':benevole_id', $_POST['benevole_id'], PDO::PARAM_STR);
                    $requete->execute();
                }
            }
        }
        ?>
    </div>
    <!-- Footer -->
    <?include "footer.php" ?>
    <script src="./js/script_faq.js"></script>
</body>
</html>
