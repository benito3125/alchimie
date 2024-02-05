<?php
// Vérifiez si l'utilisateur est connecté et s'il a le rôle d'admin
if (isset($_SESSION['user']) && $_SESSION['user'] === 'admin') {
    // Récupérez le rôle de l'utilisateur à partir de la session
    $role = $_SESSION['user'];
} else {
    $role = 'user'; // Définissez un rôle par défaut si l'utilisateur n'est pas authentifié ou n'a pas le rôle d'admin
}

// Fonction pour compter le nombre de t-shirts par taille
function countTShirtsBySize($size, $pdo) {
    $sql = "SELECT COUNT(*) AS total FROM accessoires WHERE tshirt = :size";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':size', $size, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

try {
    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tableau associatif des tailles de t-shirts
    $sizes_orange = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL','L-XS', 'L-S', 'L-M', 'L-L', 'L-XL', 'L-XXL', 'L-XXXL'];

    // Affichage du tableau des valeurs totales
    echo "<table border='1'>";
    echo "<tr><th>Taille</th><th>Total initial</th><th>Total restant</th></tr>";
    foreach ($sizes_orange as $size_orange) {
        $totalInitial = 200; // Nombre total initial de t-shirts par taille
        $totalRestant = $totalInitial - countTShirtsBySize($size_orange, $pdo);

        echo "<tr>";
        echo "<td>$size_orange</td>";
        // Afficher la valeur seulement si l'utilisateur est admin
        if ($role === 'admin') {
            echo "<td><input type='number' name='$size_orange' value='$totalInitial'></td>";
        } else {
            echo "<td>$totalInitial</td>"; // Afficher la valeur sans champ modifiable
        }
        echo "<td>$totalRestant</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Fermeture de la connexion à la base de données
    $pdo = null;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
