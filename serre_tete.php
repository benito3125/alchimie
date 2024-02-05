<?php
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

    // Fonction pour compter le nombre d'éléments par couleur de tête
    function countHeads($color, $pdo) {
        $sql = "SELECT COUNT(*) AS total FROM accessoires WHERE head = :color";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Mettre à jour le tableau des totaux restants en utilisant la fonction countHeads
    foreach ($colors as $color => $data) {
        $count = countHeads($color, $pdo);
        $colors[$color]['totalRestant'] = $data['totalInitial'] - $count;
    }

    // Affichage du tableau des statistiques
    echo "<table border='1'>";
    echo "<tr><th>Couleur</th><th>Total initial</th><th>Total restant</th>";
    // Vérification du rôle pour afficher la colonne "Action" uniquement si l'utilisateur est admin
    if ($_SESSION['user'] === 'admin') {
        echo "<th>Action</th>";
    }
    echo "</tr>";
    foreach ($colors as $color => $data) {
        // Affichage de la couleur en fonction de la valeur
        $colorLabel = ($color == '1') ? 'jaune' : 'orange';

        echo "<tr>";
        echo "<td>$colorLabel</td>";
        echo "<td>{$data['totalInitial']}</td>";
        echo "<td>{$data['totalRestant']}</td>";
        // Vérification du rôle pour afficher le bouton "Valider" uniquement si l'utilisateur est admin
        if ($_SESSION['user'] === 'admin') {
            echo "<td><button type='submit'>Valider</button></td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $pdo = null; // Fermeture de la connexion à la base de données
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
