<?php
include_once 'header.php';
try {
    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tableau associatif pour stocker les couleurs de tête et leurs totaux restants
    $colors = array(
        '1' => array('totalInitial' => 30),
        '2' => array('totalInitial' => 20)
    );

    // Si le formulaire est soumis, mettre à jour les totaux initiaux
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST['totalInitial'] as $color => $totalInitial) {
            $colors[$color]['totalInitial'] = $totalInitial;
        }
    }

    // Fonction pour compter le nombre d'éléments par couleur de cou réchauffée
    function countNeckWarmers($color, $pdo) {
        $sql = "SELECT COUNT(*) AS total FROM accessoires WHERE neck_warme = :color";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Mettre à jour le tableau des totaux restants en utilisant la fonction countNeckWarmers
    foreach ($colors as $color => $data) {
        $count = countNeckWarmers($color, $pdo);
        $colors[$color]['totalRestant'] = $data['totalInitial'] - $count;
    }

    // Affichage du formulaire et du tableau des statistiques
    echo "<form method='post'>";
    echo "<table border='1'>";
    echo "<tr><th>Couleur</th><th>Total initial</th><th>Total restant</th>";
    print_r ($_SESSION);
    // Vérification du rôle pour afficher la colonne "Action" uniquement si l'utilisateur est admin
    if ($_SESSION['user'] === 'admin') {
        echo "<th>Action</th>";
    }
    echo "</tr>";
    foreach ($colors as $color => $data) {
        // Affichage de la couleur en fonction de la valeur
        $colorLabel = ($color == '1') ? 'violet' : 'bleu';

        echo "<tr>";
        echo "<td>$colorLabel</td>";
        // Affichage du champ d'entrée uniquement si l'utilisateur est admin
        if ($_SESSION['user'] === 'admin') {
            echo "<td><input type='number' value='{$data['totalInitial']}' name='totalInitial[$color]'></td>";
        } else {
            echo "<td>{$data['totalInitial']}</td>";
        }
        echo "<td>{$data['totalRestant']}</td>";
        // Vérification du rôle pour afficher le bouton "Valider" uniquement si l'utilisateur est admin
        if ($_SESSION['user'] === 'admin') {
            echo "<td><button type='submit'>Valider</button></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";

    $pdo = null; // Fermeture de la connexion à la base de données
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
