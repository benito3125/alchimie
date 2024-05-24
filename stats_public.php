<?php
require_once 'config.php';
require_once 'link.php';
require_once 'mysql.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$jour = null;
$heure = null;
$total = null;
$autres = null;
$lsf = null;
$handi = null;


// Fonction pour établir la connexion PDO
function getConnection() {
    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// Fonction pour insérer les données dans la table visiteurs_2024
function insererDonnees($jour, $heure, $total, $autres, $lsf, $handi) {
    try {
        // Supprimer le 'h' de la valeur de l'heure et convertir en entier
        $heure = intval(str_replace('h', '', $heure));

        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO visiteurs_2024 (jour, heure, nombre_total, nombre_autres, nombre_lsf, nombre_handi) VALUES (:jour, :heure, :total, :autres, :lsf, :handi)");
        $stmt->bindParam(':jour', $jour);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':autres', $autres);
        $stmt->bindParam(':lsf', $lsf);
        $stmt->bindParam(':handi', $handi);

        // Affichage de la requête SQL
        echo $stmt->queryString;

        // Exécution de la requête
        $stmt->execute();
        return true; // Succès de l'insertion
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false; // Échec de l'insertion
    }
}



// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valider'])) {
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];
    $total = $_POST['total'];
    $autres = $_POST['autres'];
    $lsf = $_POST['lsf'];
    $handi = $_POST['handi'];

    // Appel de la fonction pour insérer les données
    if (insererDonnees($jour, $heure, $total, $autres, $lsf, $handi)) {
        echo "Données insérées avec succès dans la base de données !";
    } else {
        echo "Une erreur s'est produite lors de l'insertion des données.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require_once 'header.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accordéon de données</title>
    <style>
        /* Styles pour l'accordéon */
        .accordion {
            background-color: #f1f1f1;
            color: #333;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .active, .accordion:hover {
            background-color: #ddd;
        }

        .panel {
            padding: 0 18px;
            background-color: white;
            display: none;
            overflow: hidden;
        }

        .panel.show {
            display: block;
        }

    </style>
</head>
<body>

<h2>Accordéon de données</h2>

<?php
// Fonction pour afficher les lignes des heures dans un tableau
function afficherLignesHeures($heureDebut, $heureFin, $jour) {
    for ($heure = $heureDebut; $heure <= $heureFin; $heure++) {
        $heureStr = str_pad($heure, 2, '0', STR_PAD_LEFT) . 'h'; // Ajoute un 0 devant les heures < 10
        echo "<tr>";
        echo "<td>$heureStr</td>";
        echo "<td><input type=\"number\" name=\"$jour-$heure-total\"></td>";
        echo "<td><input type=\"number\" name=\"$jour-$heure-autres\"></td>";
        echo "<td><input type=\"number\" name=\"$jour-$heure-lsf\"></td>";
        echo "<td><input type=\"number\" name=\"$jour-$heure-handi\"></td>";
        echo "<td><button class=\"btn-valider\" onclick=\"valider(this)\" data-jour=\"$jour\" data-heure=\"$heure\">Valider</button></td>";
        echo "</tr>";
    }
}
?>

<!-- Accordéon pour Vendredi -->
<button class="accordion">Vendredi</button>
<div class="panel">
    <form method="post">
        <input type='hidden' name='jour' value='vendredi'>
        <table>
            <tr>
                <th>Heures</th>
                <th>Nombre Total</th>
                <th>Nombre Autres</th>
                <th>Nombre LSF</th>
                <th>Nombre Handi</th>
                <th>Actions</th>
            </tr>
            <?php afficherLignesHeures(14, 24, 'vendredi'); ?>
        </table>
    </form>
</div>

<!-- Accordéon pour Samedi -->
<button class="accordion">Samedi</button>
<div class="panel">
    <form method="post">
        <input type='hidden' name='jour' value='samedi'>
        <table>
            <tr>
                <th>Heures</th>
                <th>Nombre Total</th>
                <th>Nombre Autres</th>
                <th>Nombre LSF</th>
                <th>Nombre Handi</th>
                <th>Actions</th>
            </tr>
            <?php afficherLignesHeures(10, 19, 'samedi'); ?>
        </table>
    </form>
</div>

<!-- Accordéon pour Dimanche -->
<button class="accordion">Dimanche</button>
<div class="panel">
    <form method="post">
        <input type='hidden' name='jour' value='dimanche'>
        <table>
            <tr>
                <th>Heures</th>
                <th>Nombre Total</th>
                <th>Nombre Autres</th>
                <th>Nombre LSF</th>
                <th>Nombre Handi</th>
                <th>Actions</th>
            </tr>
            <?php afficherLignesHeures(10, 19, 'dimanche'); ?>
        </table>
    </form>
</div>

<script>
    // Fonction pour valider les données
    function valider(button) {
        var jour = button.getAttribute('data-jour');
        var heure = button.getAttribute('data-heure');
        var tr = button.parentNode.parentNode;
        var inputs = tr.querySelectorAll('input[type="number"]');
        var values = [];
        inputs.forEach(function(input) {
            values.push(input.value);
        });

        // Envoi des données au serveur via AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                } else {
                    console.error('Erreur lors de la requête : ' + xhr.status);
                }
            }
        };

        xhr.open('POST', window.location.href, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('jour=' + jour + '&heure=' + heure + '&total=' + values[0] + '&autres=' + values[1] + '&lsf=' + values[2] + '&handi=' + values[3]);
    }

    // Script JavaScript pour gérer l'accordéon
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
</script>

</body>
</html>
