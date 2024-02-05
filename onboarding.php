<!DOCTYPE html>
<html>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <!-- Navigation -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de fournitures du bénévole</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            margin: 50px auto;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
        }
        th {
            background-color: orange;
            color: white;
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"] {
            width: 200px;
            padding: 10px;
            margin-right: 10px;
        }
        input[type="submit"] {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
            
    <!-- Header -->
    <?php include "header.php"?>
    <h2>Formulaire de fournitures du bénévole</h2>

    <?php
    // Fonction pour insérer ou mettre à jour une ligne dans la table accessoires en fonction de l'ID_user
    // Fonction pour insérer ou mettre à jour une ligne dans la table accessoires en fonction de l'ID_user
    function insererOuMettreAJourLigneAccessoires($id_user, $tshirt, $tshirt_number, $neck_warme, $head) {
        try {
            $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour insérer ou mettre à jour une ligne dans la table accessoires
            $sql = "INSERT INTO accessoires (ID_user, Tshirt, tshirt_number, neck_warme, head) 
                    VALUES (:id_user, :tshirt, :tshirt_number, :neck_warme, :head) 
                    ON DUPLICATE KEY UPDATE 
                    Tshirt = VALUES(Tshirt), tshirt_number = VALUES(tshirt_number), neck_warme = VALUES(neck_warme), head = VALUES(head)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':tshirt', $tshirt);
            $stmt->bindParam(':tshirt_number', $tshirt_number);
            $stmt->bindParam(':neck_warme', $neck_warme);
            $stmt->bindParam(':head', $head);
            $stmt->execute();

            // Afficher ce que renvoie la fonction
            var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

        // Fermeture de la connexion
        $conn = null;
    }


    // Fonction pour afficher les informations du bénévole et le bouton "Accepter"
    function afficherInformationsBenevole($qr_code) {
        try {
            $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer les informations du bénévole
            $sql = "SELECT first_name, family_name, responsible, LSF, size, comite FROM benevoles WHERE ID = :ID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ID', $qr_code);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Affichage des données du bénévole dans un tableau
                echo "<h3>Informations du bénévole :</h3>";
                echo "<table>";
                echo "<tr><th>Prénom</th><th>Nom</th><th>Tshirt</th><th>Numéro de T-shirt</th><th>Tour de cou</th><th>Serre Tête</th><th>Action</th></tr>";
                echo "<tr>";
                echo "<td>" . $result['first_name'] . "</td>";
                echo "<td>" . $result['family_name'] . "</td>";
                echo "<td>" . $result['size'] . "</td>";
                echo "<td id='tshirt_number'>" . calculerNombreTshirt($result['size']) . "</td>";
                echo "<td>";
                
                if ($result['responsible'] == 1 && $result['comite'] == 1) {
                    echo "Violet<br>";
                } elseif ($result['responsible'] == 1 && $result['comite'] == 0) {
                    echo "Bleu<br>";
                } else {
                    echo "N/A<br>";
                }

                echo "</td>";
                echo "<td>";
                
                if ($result['comite'] == 1 && $result['LSF'] == 1) {
                    echo "Jaune<br>";
                } elseif ($result['comite'] == 0 && $result['LSF'] == 1) {
                    echo "Orange<br>";
                } else {
                    echo "N/A<br>";
                }
                
                echo "</td>";
                echo "<td><button onclick='accepterFournitures(\"$qr_code\")'>Accepter</button></td>";
                echo "</tr>";
                echo "</table>";
            } else {
                echo "<p>Aucun bénévole trouvé avec ce QR code.</p>";
            }
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }

        // Fermeture de la connexion
        $conn = null;
    }

    // Fonction pour déterminer la valeur de l'article neck_warme en fonction des valeurs de responsible, comite et LSF
    function getNeckWarmeValue($responsible, $comite, $LSF) {
        if ($responsible == 1 && $comite == 1) {
            return 1;
        } elseif ($responsible == 1 && $comite == 0) {
            return 2;
        } else {
            return 0;
        }
    }

    // Fonction pour déterminer la valeur de l'article head en fonction des valeurs de comite et LSF
    function getHeadValue($comite, $LSF) {
        if ($comite == 1 && $LSF == 1) {
            return 1;
        } elseif ($comite == 0 && $LSF == 1) {
            return 2;
        } else {
            return 0;
        }
    }

    // Fonction pour calculer le nombre de t-shirts en fonction de la taille
    function calculerNombreTshirt($size) {
        try {
            $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer le nombre de t-shirts déjà attribués pour cette taille
            $sql = "SELECT MAX(tshirt_number) AS max_tshirt_number FROM accessoires WHERE Tshirt = :size";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':size', $size);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Le nombre de t-shirts est le maximum trouvé plus un
            return $result['max_tshirt_number'] + 1;
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["qr_code"]) && !empty($_POST["qr_code"])) {
            $qr_code = $_POST["qr_code"];
            afficherInformationsBenevole($qr_code);
        } else {
            echo "<p>Le champ QR code n'a pas été correctement rempli.</p>";
        }
    }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" id="qr_code" name="qr_code" placeholder="QR code du bénévole">
        <input type="submit" value="Envoyer">
    </form>

    <script>
    function accepterFournitures(qr_code) {
        console.log("QR Code:", qr_code);
        var tshirtNumber = document.getElementById('tshirt_number').innerText.trim();
        console.log("T-shirt Number:", tshirtNumber);
        
        // Envoyer une requête AJAX pour mettre à jour la table accessoires
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Afficher un message de succès ou de traitement réussi
                    alert('Les fournitures ont été acceptées avec succès !');
                } else {
                    // Afficher un message d'erreur
                    alert('Une erreur est survenue lors de l\'acceptation des fournitures.');
                }
            }
        };
        xhr.send('qr_code=' + qr_code + '&tshirt_number=' + tshirtNumber);
    }
</script>


    <!-- Footer -->
    <?php include "footer.php"?>
</body>
</html>
