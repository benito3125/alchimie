<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style_table.css" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Repas des Bénévoles">
    <meta name="keywords" content="repas, jeu, festival, toulouse, alchimie, bénévoles">
    <title>Repas des Bénévoles</title>
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/themes/batblog/css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/56fa629212.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/bootstrap.min.js"></script>
    <script src="total.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/themes/batblog/js/theme.js"></script>
    <meta name="generator" content="Batflat" />
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.css">
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.js"></script>
</head>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-custom">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="connect.php">Retour</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
<!-- Page Header -->
<header class="intro-header"
    style="background-image: url('https://benevoles.alchimiedujeu.fr/themes/batblog/img/default-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Statistiques</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<body>
<table class="my-table">
  <tr>
    <th class="centered">ID</th>
    <th class="centered">Prénon</th>
    <th class="centered">Nom</th>
    <th class="centered">Végé</th>
    <th class="centered">Jeudi Midi</th>
    <th class="centered">Vendredi Midi</th>
    <th class="centered">Vendredi Soir</th>
    <th class="centered">Samedi Midi</th>
    <th class="centered">Dimanche Midi</th>

  </tr>
</div>

<?php
// Initialiser la session
session_start();
include "config.php";
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (isset($_SESSION["username"])) {
    // Vérification de la connexion
    /*if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } else {
    echo "Connexion réussie";
    }*/

    $sql = "SELECT * FROM benevoles";
    $result = $conn->query($sql);

    // Étape 3 : Afficher les résultats
    if ($result->num_rows > 0) {
        // Parcourir les lignes de résultat
        while ($row = $result->fetch_assoc()) {
            // Afficher chaque ligne de résultat dans une nouvelle rangée du tableau
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["family_name"] . "</td>";
            echo "<td>" . ($row["vege"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RJM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RVM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RVS"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RSM"] == 1 ? "oui" : "non") . "</td>";
            echo "<td>" . ($row["RDM"] == 1 ? "oui" : "non") . "</td>";
            echo "</tr>";
        }
        // Fermer le tableau
        echo "</>";
    } else {
        echo "0 résultats";
    }
    


    // Étape 4 : Fermer la connexion
    $conn->close();

    ?>

        <!-- Si l'utilisateur n'est pas connecté-->
        <?php
} else {

    header("Location: index.php");

    exit();
}
?>
    </body>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    
                </div>
            </div>
        </div>
    </footer>
</html>