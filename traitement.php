<?php
if (isset($_GET['id'])) {
    // Récupération des variables
    session_start();
    include "config.php";
  // Récupération des paramètres GET
  $id = $_GET['id'];


    // Récupération des données du bénévole avec l'ID scanné
    $query = "SELECT ID, vege, RJM FROM benevoles WHERE ID = :id AND RJM = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $benevole = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($benevole) {
      // Vérification de la valeur de RJM
      if ($benevole['RJM'] == 1) {
        // Mise à jour de la valeur de RJM
        $query = "UPDATE benevoles SET RJM = 2 WHERE ID = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Affichage du message de validation
        echo "Repas validé";

      } else if ($benevole['RJM'] == 2) {
        // Affichage du message d'erreur (repas déjà validé)
        echo "Repas déjà validé";
      }
    } else {
      // Affichage du message d'erreur (pas de repas prévu)
      echo "Pas de repas prévu";
    }

    // Récupération de la valeur de vege
    $query = "SELECT vege FROM benevoles WHERE ID = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $benevole_vege = $stmt->fetch(PDO::FETCH_ASSOC);

    // Affichage de la couleur de fond en fonction de la valeur de vege
    if ($benevole_vege['vege'] == 'oui') {
      echo "<script>document.body.style.backgroundColor = 'green';</script>";
    } else {
      echo "<script>document.body.style.backgroundColor = 'orange';</script>";
    }

} catch(PDOException $e) {
    // Gestion des erreurs de la connexion à la base de données
    echo "Erreur : " . $e->getMessage();
}

?>
