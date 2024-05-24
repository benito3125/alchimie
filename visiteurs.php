<?php
require_once 'header.php';
require_once 'link.php';
require_once 'mysql.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Fonction pour récupérer les données en fonction du regroupement et de la colonne de somme choisie
function getDataFromDB($groupBy, $sumColumn, $tableName) {
    try {
        $sql = "";
        switch ($groupBy) {
            case 'day':
                $sql = "SELECT jour, SUM($sumColumn) as total FROM $tableName GROUP BY jour";
                break;
            case 'year':
                $sql = "SELECT  SUM($sumColumn) as total FROM $tableName ";
                break;
            default:
                $sql = "SELECT SUM($sumColumn) as total FROM $tableName";
        }

        return getData($sql);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nombre_handi = $_POST['nombre_handi'] ?? null;
    $nombre_lsf = $_POST['nombre_lsf'] ?? null;
    $nombre_autres = $_POST['nombre_autres'] ?? null;
    $nombre_total = $_POST['nombre_total'] ?? null;
    $heure = $_POST['heure'] ?? null;
    $jour = $_POST['jour'] ?? null;

    // Insérer les données dans la table visiteurs_2024
    if (insertDataTo2024($nombre_handi, $nombre_lsf, $nombre_autres, $nombre_total, $heure, $jour)) {
        echo "Données insérées avec succès dans la table visiteurs_2024.";
    } else {
        echo "Erreur lors de l'insertion des données dans la table visiteurs_2024.";
    }
}

// Récupérer les paramètres de regroupement et de somme depuis l'URL
$groupBy = isset($_GET['group']) ? $_GET['group'] : 'year';
$sumColumn = isset($_GET['sum']) ? $_GET['sum'] : 'nombre_total';

// Récupérer les données pour l'année 2023
$data_2023 = getDataFromDB($groupBy, $sumColumn, 'visiteurs_2023');

// Récupérer les données pour l'année 2024
$data_2024 = getDataFromDB($groupBy, $sumColumn, 'visiteurs_2024');

// Pour le regroupement par jour, ajustez l'ordre des jours
if ($groupBy === 'day') {
    // Mettez le vendredi en premier, le samedi en deuxième et le dimanche en troisième
    $data_2023 = array_reverse($data_2023);
    $data_2024 = array_reverse($data_2024);
}

// Pour le regroupement par année, assurez-vous que seules les données totales sont affichées
if ($groupBy === 'year') {
    $data_2023 = array(array('total' => $data_2023[0]['total']));
    $data_2024 = array(array('total' => $data_2024[0]['total']));
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h2, h1 {
            margin-bottom: 20px;
            text-align: center
        }
        form {
            margin: 0 auto;
            display: inline-block;
        }
        label, select, input[type="submit"] {
            margin-right: 10px;
        }
        canvas {
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }
        .hidden {
            display: none;
        }

        /* Style pour aligner le contenu à gauche */
        #saisirForm {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: left;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        /* Style pour le bouton Ajouter */
        #saisirButton {
            display: block;
            margin: 20px auto; /* Ajustement de la marge pour centrer le bouton */
            padding: 15px 30px; /* Augmentation du rembourrage pour une meilleure apparence */
            background-color: #8a2be2; /* Couleur de fond violet */
            color: white; /* Couleur du texte blanc */
            border: none; /* Supprimer la bordure */
            cursor: pointer; /* Curseur de pointeur au survol */
            border-radius: 5px; /* Coins arrondis */
            font-size: 18px; /* Taille de police augmentée */
        }
         /* Style pour les champs dans la case Ajouter */
         #ajouterCase {
            border: 1px solid #ddd; /* Bordure grise */
            text-align: center; /* Centrer le contenu horizontalement */

        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <h2>Statistiques des visijoueurs</h2>
    <?php 
    // Si l'utilisateur est un administrateur, inclure le formulaire de saisie
    if ($userRole === 'admin' || $userRole === 'accueil') {
        include_once 'saisir_form.php';
     ?>
    <div class="container">

        <form action="" method="get">
            <label for="group">Regroupement :</label>
            <select name="group" id="group">
                <option value="day" <?= $groupBy === 'day' ? 'selected' : '' ?>>Par jour</option>
                <option value="year" <?= $groupBy === 'year' ? 'selected' : '' ?>>Au total par année</option>
            </select>
            <label for="sum">Sommer la colonne :</label>
            <select name="sum" id="sum">
                <option value="nombre_total" <?= $sumColumn === 'nombre_total' ? 'selected' : '' ?>>Nombre Total</option>
                <option value="nombre_autres" <?= $sumColumn === 'nombre_autres' ? 'selected' : '' ?>>Nombre de Visiteurs</option>
                <option value="nombre_lsf" <?= $sumColumn === 'nombre_lsf' ? 'selected' : '' ?>>Nombre de LSF</option>
                <option value="nombre_handi" <?= $sumColumn === 'nombre_handi' ? 'selected' : '' ?>>Nombre de Handi</option>
            </select>
            <input type="submit" value="Afficher">
        </form>
        <canvas id="myChart" width="400" height="400"></canvas>
        </div>
        <script>
    // Fonction pour afficher ou cacher le formulaire lors du clic sur le bouton "Saisir"
    document.getElementById('saisirButton').addEventListener('click', function() {
        var form = document.getElementById('saisirForm');
        form.classList.toggle('hidden');
    });

    var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?= $groupBy === 'day' ? "'" . implode("', '", array_column($data_2023, 'jour')) . "'" : "'Années'" ?>
                ],
                datasets: [{
                    label: '2023',
                    data: [<?= implode(', ', array_column($data_2023, 'total')) ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: '2024',
                    data: [<?= implode(', ', array_column($data_2024, 'total')) ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
        options: {
            legend: {
                position: 'left' // Déplace la légende à gauche du graphique
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

</script>
    <?php
    }else{
        echo ('<h1>vous n\'êtes pas abilité à saisir les heures</h1>');
    ?>
    <div class="container">

        <form action="" method="get">
            <label for="group">Regroupement :</label>
            <select name="group" id="group">
                <option value="day" <?= $groupBy === 'day' ? 'selected' : '' ?>>Par jour</option>
                <option value="year" <?= $groupBy === 'year' ? 'selected' : '' ?>>Au total par année</option>
            </select>
            <label for="sum">Sommer la colonne :</label>
            <select name="sum" id="sum">
                <option value="nombre_total" <?= $sumColumn === 'nombre_total' ? 'selected' : '' ?>>Nombre Total</option>
                <option value="nombre_autres" <?= $sumColumn === 'nombre_autres' ? 'selected' : '' ?>>Nombre de Visiteurs</option>
                <option value="nombre_lsf" <?= $sumColumn === 'nombre_lsf' ? 'selected' : '' ?>>Nombre de LSF</option>
                <option value="nombre_handi" <?= $sumColumn === 'nombre_handi' ? 'selected' : '' ?>>Nombre de Handi</option>
            </select>
            <input type="submit" value="Afficher">
        </form>
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?= $groupBy === 'day' ? "'" . implode("', '", array_column($data_2023, 'jour')) . "'" : "'Années'" ?>
                ],
                datasets: [{
                    label: '2023',
                    data: [<?= implode(', ', array_column($data_2023, 'total')) ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: '2024',
                    data: [<?= implode(', ', array_column($data_2024, 'total')) ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    position: 'left' // Déplace la légende à gauche du graphique
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


    </script>
    <?php }?>
</body>
<?php include_once 'footer.php'; ?>
</html>
