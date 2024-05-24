<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config.php";
function mapTailleToStockVariable($size) {
    $size = strtolower($size);
    $stock_variables = [
        'xs' => 'stock_tshirt_xs',
        's' => 'stock_tshirt_s',
        'm' => 'stock_tshirt_m',
        'l' => 'stock_tshirt_l',
        'xl' => 'stock_tshirt_xl',
        'xxl' => 'stock_tshirt_xxl',
        'xxxl' => 'stock_tshirt_xxxl',
        'l-xs' => 'stock_tshirt_lxs',
        'l-s' => 'stock_tshirt_ls',
        'l-m' => 'stock_tshirt_lm',
        'l-l' => 'stock_tshirt_ll',
        'l-xl' => 'stock_tshirt_lxl',
        'l-xxl' => 'stock_tshirt_lxxl',
        'l-xxxl' => 'stock_tshirt_lxxxl',
        'l-int-xs' => 'stock_tshirt_lintxs',
        'l-int-s' => 'stock_tshirt_lints',
        'l-int-m' => 'stock_tshirt_lintm',
        'l-int-l' => 'stock_tshirt_lintl',
        'l-int-xl' => 'stock_tshirt_lintxl',
        'l-int-xxl' => 'stock_tshirt_lintxxl',
        'l-int-xxxl' => 'stock_tshirt_lintxxxl',
        // Ajoutez d'autres tailles au besoin
    ];

    // Vérifie si la taille est définie dans le tableau
    if (array_key_exists($size, $stock_variables)) {
        // Récupère le nom de la variable de stock
        $stock_variable_name = $stock_variables[$size];
        // Vérifie si la variable de stock est définie
        if (isset($GLOBALS[$stock_variable_name])) {
            // Retourne la valeur de stock correspondante
            return $GLOBALS[$stock_variable_name];
        } else {
            // Si la variable de stock n'est pas définie, retourne null
            return null;
        }
    } else {
        // Si la taille n'est pas définie, retourne null
        return null;
    }
}


$stock_tshirt_xs = 25;
$stock_tshirt_s = 57;
$stock_tshirt_m = 100;
$stock_tshirt_l = 115;
$stock_tshirt_xl = 100;
$stock_tshirt_xxl = 34;
$stock_tshirt_xxxl = 29;
$stock_tshirt_lxs = 3;
$stock_tshirt_ls = 9;
$stock_tshirt_lm = 14;
$stock_tshirt_ll = 12;
$stock_tshirt_lxl = 7;
$stock_tshirt_lxxl = 2;
$stock_tshirt_lxxxl = 2;
$stock_tshirt_lintxs = 2;
$stock_tshirt_lints = 16;
$stock_tshirt_lintm = 26;
$stock_tshirt_lintl = 20;
$stock_tshirt_lintxl = 13;
$stock_tshirt_lintxxl = 6;
$stock_tshirt_lintxxxl = 4;

// Redirection vers la même page pour actualiser le contenu
function redirectToSelf() {
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

function getIdUtilisateur($first_name, $family_name) {
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer l'ID de l'utilisateur par son nom et prénom
        $sql = "SELECT ID FROM benevoles WHERE first_name = :first_name AND family_name = :family_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':family_name', $family_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['ID'];
        } else {
            return null;
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        return null;
    }
}

// Fonction pour insérer ou mettre à jour une ligne dans la table accessoires en fonction de l'ID_user
function insererOuMettreAJourLigneAccessoires ($id_user, $tshirt, $tshirt_number, $neck_warm, $head) {
   
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définir les valeurs par défaut pour :neck_warm et :head
        $neck_warm_value = 0;
        $head_value = 0;
        $bracelet = 1;

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
        $sql = "INSERT INTO accessoires (ID_user, Tshirt, tshirt_number, neck_warme, head, bracelet) 
                VALUES (:id_user, :tshirt, :tshirt_number, :neck_warme, :head, :bracelet) 
                ON DUPLICATE KEY UPDATE 
                Tshirt = VALUES(Tshirt), tshirt_number = VALUES(tshirt_number), neck_warme = VALUES(neck_warme), head = VALUES(head), bracelet = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':tshirt', $tshirt);
        $stmt->bindParam(':tshirt_number', $tshirt_number);
        $stmt->bindParam(':neck_warme', $neck_warm_value); // Utilisation de la valeur calculée
        $stmt->bindParam(':head', $head_value); // Utilisation de la valeur calculée
        $stmt->bindParam(':bracelet', $bracelet);
        $stmt->execute();

        // Récupérer les valeurs des colonnes vege, RJM, RVM, RVS, RSM, RDM de la table benevoles pour l'ID spécifié
        $sql_select_benevoles = "SELECT vege, RJM, RVM, RVS, RSM, RDM FROM benevoles WHERE ID = :id_user";
        echo $sql_select_benevoles;
        $stmt_select_benevoles = $conn->prepare($sql_select_benevoles);
        $stmt_select_benevoles->bindParam(':id_user', $id_user);
        $stmt_select_benevoles->execute();
        $row = $stmt_select_benevoles->fetch(PDO::FETCH_ASSOC);

        // Enregistrez les valeurs dans des variables
        $vege = $row['vege'];
        $RJM = $row['RJM'];
        $RVM = $row['RVM'];
        $RVS = $row['RVS'];
        $RSM = $row['RSM'];
        $RDM = $row['RDM'];

        // Insertion ou mise à jour des données dans la table benevoles_2024
        $sql_benevoles_2024 = "INSERT INTO benevoles_2024 (ID, vege, RJM, RVM, RVS, RSM, RDM) 
                VALUES (:ID, :vege, :RJM, :RVM, :RVS, :RSM, :RDM) 
                ON DUPLICATE KEY UPDATE 
                vege = VALUES(vege), RJM = VALUES(RJM), RVM = VALUES(RVM), RVS = VALUES(RVS), RSM = VALUES(RSM), RDM = VALUES(RDM)";
        $stmt_benevoles_2024 = $conn->prepare($sql_benevoles_2024);
        $stmt_benevoles_2024->bindParam(':ID', $id_user);
        $stmt_benevoles_2024->bindParam(':vege', $vege);
        $stmt_benevoles_2024->bindParam(':RJM', $RJM);
        $stmt_benevoles_2024->bindParam(':RVM', $RVM);
        $stmt_benevoles_2024->bindParam(':RVS', $RVS);
        $stmt_benevoles_2024->bindParam(':RSM', $RSM);
        $stmt_benevoles_2024->bindParam(':RDM', $RDM);
        $stmt_benevoles_2024->execute();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion
    $conn = null;
}



// Fonction pour afficher les informations du bénévole et les boutons "Accepter" et "Modifier"
function afficherInformationsBenevole($id_user, $action = "") {
    if ($id_user) {
        try {
            $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer les informations du bénévole par son ID
            $sql = "SELECT ID, first_name, family_name, responsible, LSF, size, comite, mineur FROM benevoles WHERE ID = :id_user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
            // Vérifier si la taille est "externe"
            $size = $result['size'] === 'externe' ? 'N/A' : $result['size'];
            $neck_warm = null;
            if ($result['responsible'] == 1 && $result['comite'] == 1) {
                $neck_warm = 'Violet';
            } elseif ($result['responsible'] == 1 && $result['comite'] == 0) {
                $neck_warm = 'Bleu';
            } else {
                $neck_warm = 'N/A';
            }

            $mineur_txt = ""; // Define $mineur_txt initially

            if ($result['mineur'] == 1){
                $mineur_txt = "est mineure"; // Assign the appropriate value
            } elseif ($result['mineur'] == 0) {
                $mineur_txt = "est majeure"; // Assign the appropriate value
            }

            $head = null;
            if ($result['responsible'] == 1 && $result['LSF'] == 1) {
                $head = 'Jaune';
            } elseif ($result['comite'] == 0 && $result['LSF'] == 1) {
                $head = 'Orange';
            } else {
                $head = 'N/A';
            }

            echo "<h1>Cette personne $mineur_txt</h1>";
            // Affichage des données du bénévole dans un tableau
            echo "<h3>Informations du bénévole :</h3>";
            echo "<table>";
            echo "<tr><th>QR-Code</th><th>Prénom</th><th>Nom</th><th>Tshirt</th><th>Numéro de T-shirt</th><th>Tour de cou</th><th>Serre Tête</th><th>Bracelet</th><th>Action</th></tr>";
            echo "<tr>";
            echo "<form method=\"post\">";
            echo "<td><input type='text' name='id_user' value='$id_user' readonly class='border_data'></td>";
            echo "<td>" . $result['first_name'] . "</td>";
            echo "<td>" . $result['family_name'] . "</td>";

            // Rendre l'input "size" modifiable si l'action est "modifier"
            if ($action === "modifier") {
                echo "<td><input type='text' name='size' value='$size' class='border_data'></td>";
            } else {
                echo "<td><input type='text' name='size' value='$size' readonly class='border_data'></td>";
            }

            // Si la taille n'est pas "externe", calculer le nombre de t-shirts
            if ($result['size'] !== 'externe') {
                $tshirt_number = calculerNombreTshirt($result['size']);
            } else {
                $tshirt_number = null; // Définir sur NULL ou une valeur par défaut appropriée dans la base de données
                // Si la taille est "externe", ajouter le bénévole à benevoles_2024
                insererOuMettreAJourLigneAccessoires($id_user, $size, $tshirt_number, $neck_warm, $head);
            }

            echo "<td><input type='text' name='tshirt_number' value='$tshirt_number' readonly class='border_data'></td>";
            echo "<td><input type='text' name='neck_warm' value='$neck_warm' readonly class='border_data'></td>";
            echo "<td><input type='text' name='head' value='$head' readonly class='border_data'></td>";
            echo "<td><input type='text' name='bracelet' value='1' readonly class='border_data'></td>";
            // Afficher le bouton "OK" si l'action est "modifier", sinon afficher les boutons "Accepter" et "Modifier"
            if ($action === "modifier" || $action === "accepter") {
                echo "<td>  
                            <button type='submit' name='ok'>
                                <img src='icons/check.png' alt='ok' width='32' height='32'>
                            </button>
                        </td>";
                // Ajoutez le bouton "Annuler"
                echo "<td>  
                            <button type='submit' name='annuler'>
                                Annuler
                            </button>
                        </td>";
            } else {
                echo "<td>  
                            <button type='submit' name='accepter'>
                                <img src='icons/check.png' alt='Accepter' width='32' height='32'>
                            </button>";

                // Vérifier si l'ID du bénévole est présent dans la table accessoires
                $accessoires_query = $conn->prepare("SELECT * FROM accessoires WHERE ID_user = :ID_user");
                $accessoires_query->bindParam(':ID_user', $id_user);
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
            echo "<p>Aucun bénévole trouvé avec cet ID.</p>";
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Fermeture de la connexion
    $conn = null;
    } else {
        echo "<p>Aucun bénévole trouvé avec ce nom et prénom.</p>";
    }
}




function calculerNombreTshirt($size) {

    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer le nombre de t-shirts déjà attribués pour cette taille
        $sql = "SELECT MAX(tshirt_number) AS max_tshirt_number FROM accessoires WHERE tshirt = :size";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':size', $size);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du stock disponible
        $stock_variable = mapTailleToStockVariable($size);
        if ($stock_variable !== null) {
            $stock_disponible = $stock_variable - $result['max_tshirt_number'];
            $fournir = $result['max_tshirt_number'] + 1;
            echo "Il reste  ".$stock_disponible."  t-shirt de taill  ".$size;
            if ($stock_disponible <= 0) {
                return "Stock épuisé";
            }
            return $fournir;
        } else {
            return "Taille non valide";
            return null;
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        return null;
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

    //actions du bouton accepter sans modification
    if (isset($_POST["accepter"]) && isset($_POST["id_user"]) && !empty($_POST["id_user"])){
        $id_user = $_POST['id_user'];
            $tshirt = $_POST['size'];
            $tshirt_number = $_POST['tshirt_number'];
            $neck_warm = $_POST['neck_warm'];
            $head = $_POST['head'];
            
            echo 
            // Insérer ou mettre à jour les données dans la table "accessoires"
            insererOuMettreAJourLigneAccessoires($id_user, $tshirt, $tshirt_number, $neck_warm, $head);
            
            // Rafraîchir la page
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
    }

    //actions du bouton modifier
    if (isset($_POST["modifier"]) && isset($_POST["id_user"]) && !empty($_POST["id_user"])){
        afficherInformationsBenevole($_POST["id_user"], "modifier");
    }

    //actions du bouton ok lorsqu'on modifie la taille du tshirt
    if (isset($_POST["ok"]) && isset($_POST["id_user"]) && !empty($_POST["id_user"])){
        $id_user = $_POST['id_user'];
        $tshirt = $_POST['size'];
        $tshirt_number = $_POST['tshirt_number'];
        $neck_warm = $_POST['neck_warm'];
        $head = $_POST['head'];
        
        // Mettre à jour la taille dans la table "benevoles"
        mettreAJourTailleBenevole($id_user, $tshirt);

        // Recalculer le nombre de t-shirts en fonction de la nouvelle taille
        $tshirt_number = calculerNombreTshirt($tshirt);

        // Réafficher les informations du bénévole après avoir inséré ou mis à jour les données
        afficherInformationsBenevole($id_user);
    }
    if (isset($_POST["prenom"]) && !empty($_POST["prenom"]) && isset($_POST["nom"]) && !empty($_POST["nom"])) {
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];   
        $id_user = getIdUtilisateur($prenom, $nom);     
        //echo $prenom;
        //echo $nom;
        //echo $id_user;

         // Gestion de l'action "modifier"
        if (isset($_POST["ok"])) { // Gestion de l'action "ok"
            $id_user = $_POST['id_user'];
            $tshirt = $_POST['size'];
            $tshirt_number = $_POST['tshirt_number'];
            $neck_warm = $_POST['neck_warm'];
            $head = $_POST['head'];
            
            // Mettre à jour la taille dans la table "benevoles"
            mettreAJourTailleBenevole($id_user, $tshirt);

            // Recalculer le nombre de t-shirts en fonction de la nouvelle taille
            $tshirt_number = calculerNombreTshirt($tshirt);

            // Réafficher les informations du bénévole après avoir inséré ou mis à jour les données
            afficherInformationsBenevole($id_user);
        } else {
            afficherInformationsBenevole($id_user);
        }
    } else {
        echo "<p>Le champ QR code n'a pas été correctement rempli.</p>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once 'link.php' ?>
    <style>
        .onboarding {
            position: relative;
            width: 100%;
            height: 100vh; /* Utiliser toute la hauteur de la fenêtre */
            text-align: center; /* Centrer horizontalement */
        }

        .container_onboarding {
            position: absolute;
            top: 50%; /* Centrer verticalement */
            left: 50%; /* Centrer horizontalement */
            transform: translate(-50%, -50%); /* Centrage précis */
        }

        /* Style du bouton Retour */
        .button-container {
            text-align: center; /* Centrer horizontalement */
            margin-top: 20px; /* Marge supérieure pour l'éloigner du haut de la page */
        }
        .annuler-button{
            margin-top: 50px; /* Ajustez la marge entre les boutons selon vos besoins */

        }
        .return-button,
        .annuler-button {
            position: fixed;
            top: 0;
            right: 10px;
            background-color: #f28832;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>

<body class="onboarding">
    <h2>Formulaire de fournitures du bénévole</h2>
    <div class="container_onboarding">
        <!-- Contenu du formulaire -->
        
        <div style="flex-basis: 48%;">
            <!--<ol style="text-align: left;">
                <li>Récupérer le badge du bénévole</li>
                <li>Scanner son QR-Code</li>
                <li>Préciser au bénévole la taille, si besoin la modifier avec l'outil</li>
                <li>Donner le numéro de t-shirt correspondant et des autres accessoires le cas échéant.</li>
                <li>Ne valider que lorsque le matériel a été donné !</li>
            </ol>-->
        </div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" id = "prenom" name="prenom" placeholder="Prénom">
        <input type="text" id = "nom" name="nom" placeholder="Nom">
        <input type="submit" value="Rechercher">
    </form>
    </div>

    <!-- Structure du conteneur pour les boutons -->
    <div class="button-container">
        <!-- Bouton Retour -->
        <form action="connecte.php">
            <input type="submit" value="Retour" class="return-button button-size">
        </form>
        
        <!-- Bouton Annuler -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="submit" value="Annuler" name="annuler" class="annuler-button" id="annulerButton" style="<?php echo isset($_POST["id_user"]) ? 'display: inline-block;' : 'display: none;'; ?>">
        </form>


    </div>


</body>
</html>
