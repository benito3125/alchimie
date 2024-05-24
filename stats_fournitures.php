<!DOCTYPE html>
<html>
<head>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <title>Statistiques des t-shirts</title>
    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .accordion.active, .accordion:hover {
            background-color: #ccc;
        }

        .panel {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }
    </style>
</head>
<body>
    <?php include_once "header.php" ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>Statistiques des fournitures</h2>

                <!-- Statistiques des bracelets-->
                <button class="accordion">Bracelets</button>
                <div class="panel">
                    <h3></h3>
                    <?php
                    $totalInitialBracelet = 800;
                    require_once 'mysql.php';

                    try {
                        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Requête SQL pour obtenir le total après soustraction
                        $sqlTotalApresSoustraction = "SELECT COUNT(*) AS total_bracelet FROM accessoires WHERE bracelet = 1";
                        $stmtTotalApresSoustraction = $conn->prepare($sqlTotalApresSoustraction);
                        $stmtTotalApresSoustraction->execute();
                        $totalBracelet = $stmtTotalApresSoustraction->fetch(PDO::FETCH_ASSOC)['total_bracelet'];

                        // Affichage des totaux
                        echo "<p>Total initial : $totalInitialBracelet</p>";
                        echo "<p>Total restant : " . ($totalInitialBracelet - $totalBracelet) . "</p>";

                    } catch(PDOException $e) {
                        echo "Erreur : " . $e->getMessage();
                    }
                    ?>
                </div>
                <!-- Ajout d'une entrée pour les t-shirts -->
                <button class="accordion">T-shirts</button>
                <div class="panel">
                    <?php include "tableau_tshirt.php" ?>
                </div>            

                <!-- Ajout d'une entrée pour les serre-têtes -->
                <button class="accordion">Serre-têtes</button>
                <div class="panel">
                    <?php include "serre_tete.php" ?>
                </div>
                
                <!-- Ajout d'une entrée pour les tour de cou -->
                <button class="accordion">Tour de cou</button>
                <div class="panel">
                    <?php include "neck_warme.php" ?>
                </div> 

                <!-- Ajout de l'élément pour les statistiques -->
                
            </div>
        </div>
    </div>

    <script src="./js/script_stats_fournitures.js"></script>
    <?php include "footer.php" ?>
</body>
</html>
