<?php
require_once 'mysql.php';
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <title>FAQ</title>
    <style>
        /* Style inspiré d'ASOS */
        body {
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px 0;
        }
        .tile {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            box-sizing: border-box;
            cursor: pointer; /* Curseur pointer pour indiquer que c'est cliquable */
        }
        .question {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }
        .answer {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            display: none; /* Réponses initialement masquées */
        }
        /* Style pour le header et le footer */
        header {
            background-color: #333;
            color: #orange;
            padding: 10px 0;
            text-align: center;
        }

        /* Style pour le bouton Ajouter */
        #ajouterBtn {
            display: block;
            margin: 20px auto; /* Ajustement de la marge pour centrer le bouton */
            padding: 15px 30px; /* Augmentation du rembourrage pour une meilleure apparence */
            background-color: #861773; /* Couleur de fond violet */
            color: #F18932; /* Couleur du texte orange */
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
    </style>
</head>
<body>
    <!-- Header -->
    <?php include "header.php"?>
    <?php
    // Vérifiez si l'utilisateur est admin
    if ($userRole === 'admin') {
        echo '<button id="ajouterBtn">Ajouter</button>';
    }
    ?>
    <!-- Case avec les champs (initiallement masquée) -->
    <div id="ajouterCase" class="container" style="display: none;">
        <h2>Ajouter une question</h2>
        <form id="ajouterForm" enctype="multipart/form-data">
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie">
                <option value="Accueil bénévole">Accueil bénévole</option>
                <option value="Accueil visiteur">Accueil visiteur</option>
                <option value="Tournois">Tournois</option>
                <option value="Juridique">Juridique</option>
                <option value="TAPAJ">TAPAJ</option>
                <option value="BAJ">BAJ</option>
                <option value="Goodies">Goodies</option>
                <option value="Communication">Communication</option>
                <option value="Logistique">Logistique</option>
                <option value="Buvettes">Buvettes</option>
                <option value="Repas">Repas</option>
                <option value="Boite à outils">Boite à outils</option>
            </select>
            <br>
            <label for="question">Question :</label>
            <input type="text" id="question" name="question" required>
            <br>
            <label for="reponse">Réponse :</label>
            <textarea id="reponse" name="reponse" required></textarea>
            <br>
            <label for="file">Fichier :</label>
            <input type="file" id="file" name="file">
            <br>
            <button id="ajouterBtn" type="button" onclick="ajouterQuestion()">Envoyer</button>
        </form>
    </div>
    <div class="container">
        <?php
        // Récupérer les données de la FAQ
        $faqData = getFAQData();

        // Vérifier s'il y a des données à afficher
        if ($faqData) {
            // Afficher chaque tag avec ses questions et réponses correspondantes
            foreach ($faqData as $tag => $questions) {
                echo "<div class='tile'>";
                echo "<h2>$tag</h2>";
                foreach ($questions as $qa) {
                    echo "<div class='question'>" . $qa['query'] . "</div>";
                    echo "<div class='answer'>" . $qa['response'];
                    // Vérifier si un fichier est associé à la réponse
                    if (!empty($qa['file'])) {
                        echo "<a href='" . $qa['file'] . "' target='_blank'>Télécharger le fichier</a>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "Aucune question trouvée.";
        }
        ?>
    </div>

    <!-- Footer -->
    <?include "footer.php" ?>
    <script src="./js/script_faq.js"></script>
</body>
</html>
