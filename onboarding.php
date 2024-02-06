<?php
session_start(); // Démarrez la session si ce n'est pas déjà fait
require_once "config.php";

// Redirection vers la même page pour actualiser le contenu
function redirectToSelf() {
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Fonction pour insérer ou mettre à jour une ligne dans la table accessoires en fonction de l'ID_user
function insererOuMettreAJourLigneAccessoires($id_user, $tshirt, $tshirt_number, $neck_warm, $head) {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définir les valeurs par défaut pour :neck_warme et :head
        $neck_warm_value = 0;
        $head_value = 0;

        // Assigner les valeurs en fonction des conditions spécifiées
        if ($neck_warm === 'Violet') {
            $neck_warm_value = 1;
        } elseif ($neck_warm === 'Bleu') {
            $neck_warm_value = 2;
        }

        if ($head === 'Jaune') {
            $head_value = 1;
        } elseif ($head === 'Orange') {
            $head_value = 2;
        }

        // Requête pour insérer ou mettre à jour une ligne dans la table accessoires
        $sql = "INSERT INTO accessoires (ID_user, Tshirt, tshirt_number, neck_warme, head) 
                VALUES (:id_user, :tshirt, :tshirt_number, :neck_warme, :head) 
                ON DUPLICATE KEY UPDATE 
                Tshirt = VALUES(Tshirt), tshirt_number = VALUES(tshirt_number), neck_warme = VALUES(neck_warme), head = VALUES(head)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':tshirt', $tshirt);
        $stmt->bindParam(':tshirt_number', $tshirt_number);
        $stmt->bindParam(':neck_warme', $neck_warm_value); // Utilisation de la valeur calculée
        $stmt->bindParam(':head', $head_value); // Utilisation de la valeur calculée
        $stmt->execute();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion
    $conn = null;
}

// Fonction pour afficher les informations du bénévole et le bouton "Accepter"
// Fonction pour afficher les informations du bénévole et les boutons "Accepter" et "Modifier"
function afficherInformationsBenevole($qr_code, $action = "") {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer les informations du bénévole
        $sql = "SELECT first_name, family_name, responsible, LSF, size, comite FROM benevoles WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $qr_code);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $size = $result['size'];
            $neck_warm = null;
            if ($result['responsible'] == 1 && $result['comite'] == 1) {
                $neck_warm = 'Violet';
            } elseif ($result['responsible'] == 1 && $result['comite'] == 0) {
                $neck_warm = 'Bleu';
            } else {
                $neck_warm = 'N/A';
            }
            $head = null;
            if ($result['responsible'] == 1 && $result['LSF'] == 1) {
                $head = 'Jaune';
            } elseif ($result['comite'] == 0 && $result['LSF'] == 1) {
                $head = 'Orange';
            } else {
                $head = 'N/A';
            }

            $tshirt_number = calculerNombreTshirt($result['size']);
            // Affichage des données du bénévole dans un tableau
            echo "<h3>Informations du bénévole :</h3>";
            echo "<table>";
            echo "<tr><th>QR-Code</th><th>Prénom</th><th>Nom</th><th>Tshirt</th><th>Numéro de T-shirt</th><th>Tour de cou</th><th>Serre Tête</th><th>Action</th></tr>";
            echo "<tr>";
            echo "<form method=\"post\">";
            echo "<td><input type='text' name='qr_code' value='$qr_code' readonly class='border_data'></td>";
            echo "<td>" . $result['first_name'] . "</td>";
            echo "<td>" . $result['family_name'] . "</td>";

            // Rendre l'input "size" modifiable si l'action est "modifier"
            if ($action === "modifier") {
                echo "<td><input type='text' name='size' value='$size' class='border_data'></td>";
            } else {
                echo "<td><input type='text' name='size' value='$size' readonly class='border_data'></td>";
            }

            echo "<td><input type='text' name='tshirt_number' value='$tshirt_number' readonly class='border_data'></td>";
            echo "<td><input type='text' name='neck_warm' value='$neck_warm' readonly class='border_data'></td>";
            echo "<td><input type='text' name='head' value='$head' readonly class='border_data'></td>";

            // Afficher le bouton "OK" si l'action est "modifier", sinon afficher les boutons "Accepter" et "Modifier"
            if ($action === "modifier") {
                echo "<td>  <button type='submit' name='ok'>
                                <img src='icons/check.png' alt='ok' width='32' height='32'>
                            </button>
                        </td>";
            } else {
                echo "<td>  
                            <button type='submit' name='accepter'>
                                <img src='icons/check.png' alt='Accepter' width='32' height='32'>
                            </button>";

                // Vérifier si l'ID du bénévole est présent dans la table accessoires
                $accessoires_query = $conn->prepare("SELECT * FROM accessoires WHERE ID_user = :ID_user");
                $accessoires_query->bindParam(':ID_user', $qr_code);
                $accessoires_query->execute();
                $accessoire_result = $accessoires_query->fetch(PDO::FETCH_ASSOC);
                
                // Si l'ID est trouvé, afficher le bouton "Modifier"
                if ($accessoire_result) {
                    echo "  <button type='submit' name='modifier'>
                                <img src='icons/change.png' alt='modifier' width='32' height='32'>
                            </button>
                ";
                }
                echo "</td>";
            }
            echo "</form>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "<p>Aucun bénévole trouvé avec ce QR code.</p>";
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Fermeture de la connexion
    $conn = null;
}


// Fonction pour calculer le nombre de t-shirts en fonction de la taille
function calculerNombreTshirt($size) {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer le nombre de t-shirts déjà attribués pour cette taille
        $sql = "SELECT MAX(tshirt_number) AS max_tshirt_number FROM accessoires WHERE Tshirt = :size";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':size', $size);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Le nombre de t-shirts est le maximum trouvé plus un
        return $result['max_tshirt_number'] + 1;
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}

// Fonction pour mettre à jour la valeur de "size" dans la table "benevoles"
function mettreAJourTailleBenevole($id_user, $new_size) {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour mettre à jour la valeur de "size" dans la table "benevoles"
        $sql = "UPDATE benevoles SET size = :new_size WHERE ID = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':new_size', $new_size);
        $stmt->execute();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion
    $conn = null;
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["qr_code"]) && !empty($_POST["qr_code"])) {
        $qr_code = $_POST["qr_code"];
        
        // Gestion de l'action "modifier"
        if (isset($_POST["modifier"])) {
            afficherInformationsBenevole($qr_code, "modifier");
        } elseif (isset($_POST["ok"])) { // Gestion de l'action "ok"
            $id_user = $_POST['qr_code'];
            $tshirt = $_POST['size'];
            $tshirt_number = $_POST['tshirt_number'];
            $neck_warm = $_POST['neck_warm'];
            $head = $_POST['head'];
            
            // Mettre à jour la taille dans la table "benevoles"
        mettreAJourTailleBenevole($id_user, $tshirt);

        // Recalculer le nombre de t-shirts en fonction de la nouvelle taille
        $tshirt_number = calculerNombreTshirt($tshirt);

        // Réafficher les informations du bénévole après avoir inséré ou mis à jour les données
        afficherInformationsBenevole($qr_code);

            afficherInformationsBenevole($qr_code);
        } elseif (isset($_POST["accepter"])) { // Gestion de l'action "accepter"
            $id_user = $_POST['qr_code'];
            $tshirt = $_POST['size'];
            $tshirt_number = $_POST['tshirt_number'];
            $neck_warm = $_POST['neck_warm'];
            $head = $_POST['head'];
            
            // Insérer ou mettre à jour les données dans la table "accessoires"
            insererOuMettreAJourLigneAccessoires($id_user, $tshirt, $tshirt_number, $neck_warm, $head);
            
            // Rafraîchir la page
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            afficherInformationsBenevole($qr_code);
        }
    } else {
        echo "<p>Le champ QR code n'a pas été correctement rempli.</p>";
    }
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil bénévoles</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Repas des Bénévoles">
    <meta name="keywords" content="repas, jeu, festival, toulouse, alchimie, bénévoles">
    <meta name="generator" content="Batflat" />

    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/bootstrap.min.js"></script>
    <!--<script src="total.js"></script>-->
    <script src="https://benevoles.alchimiedujeu.fr/themes/batblog/js/theme.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.js"></script>


    <!-- Mise en page -->
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/themes/batblog/css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/56fa629212.css">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #ffa500; /* Bordure orange pour le tableau */
            margin: 0 auto; /* Centrer le tableau */
            margin-bottom: 60px; /* Ajouter un espace de 20px en bas du tableau */
            padding: 10px; /* Ajouter des bordures de 10px de chaque côté */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            
        }

        th {
            background-color: #ffa500; /* Fond orange pour les entêtes */
            color: white; /* Texte blanc pour les entêtes */
            align-items: center; /* Centrer le texte des entêtes */
        }

        tr:hover {
            background-color: #f5f5f5;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative; /* Ajout d'une position relative pour que le bouton soit positionné correctement */
        }
        h2 {
            margin-top: 0;
            font-size: 39px; /* Vous pouvez ajuster la taille en fonction de vos préférences */
        }

        p {
            font-size: 36px; /* Vous pouvez ajuster la taille en fonction de vos préférences */
        }
        .container {
            width: 50   %;
            max-width: 500px;
            margin: 0 auto; /* Centrer le tableau */
            padding: 10px; /* Ajouter des bordures de 10px de chaque côté */
            margin-bottom: 20px; /* Ajouter un espace de 20px en bas du tableau */
        }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        input.border_data {
            width: 100%;
            padding: 5px;
            text-align: center; /* Centrer le texte */
            background-color: transparent; /* Fond transparent */
            border: none; /* Pas de bordure */
        }
        input.border_input {
            width: 70%;
            padding: 10px;
            margin-right: 10px;
        }
        input[type="submit"] {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .return-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: orange;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Formulaire de fournitures du bénévole</h2>
    <div class="container">
    <div>
        <!-- Contenu du formulaire -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" id="qr_code" name="qr_code" placeholder="QR code du bénévole" class ="border_input">
            <input type="submit" value="Envoyer">
        </form>
    </div>
    <div style="flex-basis: 48%;">
        <ol style="text-align: left;">
            <li>Récupérer le badge du bénévole</li>
            <li>Scanner son QR-Code</li>
            <li>Préciser au bénévole la taille, si besoin la modifier avec l'outil</li>
            <li>Donner le numéro de t-shirt correspondant et des autres accessoires le cas échéant.</li>
            <li>Ne valider que lorsque le matériel a été donné !</li>
        </ol>
    </div>
</div>

</div>

        </div>

    </div>

    <!-- Bouton Retour -->
    <form action="connecte.php">
        <input type="submit" value="Retour" class="return-button">
    </form>


</body>
<?php include "footer.php"?>
</html>