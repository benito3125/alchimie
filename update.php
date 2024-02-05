<!DOCTYPE html>
<html>

	<!-- Inclusion des scripts et liens -->
	<?php include "link.php"?>
	<head>
		<link rel="stylesheet" href="../css/style-qrcode.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="instascan.min.js"></script>
        <link rel="stylesheet" href="../css/style_table.css" />
        <link rel="stylesheet" href="../css/style.css" />
		<audio id="beep" src="/audio/bip.mp3"></audio>
        
		<html>
 

	</head>
    <!-- Navigation -->
    <?php include "nav.php"?>
    <!-- Header -->
    <?php include "header.php"?>
    <?php include "mealsServices.php"?>

<body>

	<div>
		<p>Cette page vous permet d'ajouter des repas à un utilisateur si ils sont manquants.</p>
        <form method="POST" action="">
  <label for="first_name">Prénom :</label>
  <input type="text" name="first_name" id="first_name">
  <label for="family_name">Nom de famille :</label>
  <input type="text" name="family_name" id="family_name">
  <button type="submit" name="submit">Rechercher</button>
</form>

<BR>

<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
	if(isset($_SESSION["username"])){ 
		// Vérification de la connexion
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		} else {
		echo "Connexion réussie";
		}
?>


<?php
if(isset($_POST['submit'])) {
  // Récupérer le prénom et le nom de famille entrés
  $first_name = $_POST['first_name'];
  $family_name = $_POST['family_name'];

  //echo($first_name);
  //echo($family_name);

  // Vérifier s'il y a des utilisateurs correspondant aux critères de recherche
    $sql="SELECT * FROM benevoles WHERE first_name = '$first_name' AND family_name = '$family_name'";
  $result = $conn->query($sql);
  //echo($sql);
  // Afficher les résultats sous forme de tableau
 // Afficher les résultats sous forme de tableau
  // Afficher les résultats sous forme de tableau
  if($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Prénom</th><th>Nom de famille</th><th>RJM</th><th>RVM</th><th>RVS</th><th>RSM</th><th>RDM</th></tr>";
    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['first_name'] . "</td>";
      echo "<td>" . $row['family_name'] . "</td>";
      echo "<td" . ($row['RJM'] == 0 ? " class='arrow' onclick='addOne(this)'" : "") . ">" . ($row["RJM"] == 2 ? "consommé" : "") . ($row['RJM'] == 1 ? "Prévu" : "") . "</td>";
      echo "<td" . ($row['RVM'] == 0 ? " class='arrow' onclick='addOne(this)'" : "") . ">" . ($row["RVM"] == 2 ? "consommé" : "") . ($row['RVM'] == 1 ? "Prévu" : "") . "</td>";
      echo "<td" . ($row['RVS'] == 0 ? " class='arrow' onclick='addOne(this)'" : "") . ">" . ($row["RVS"] == 2 ? "consommé" : "") . ($row['RVS'] == 1 ? "Prévu" : "") . "</td>";
      echo "<td" . ($row['RSM'] == 0 ? " class='arrow' onclick='addOne(this)'" : "") . ">" . ($row["RSM"] == 2 ? "consommé" : "") . ($row['RSM'] == 1 ? "Prévu" : "") . "</td>";
      echo "<td" . ($row['RDM'] == 0 ? " class='arrow' onclick='addOne(this)'" : "") . ">" . ($row["RDM"] == 2 ? "consommé" : "") . ($row['RDM'] == 1 ? "Prévu" : "") . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>Aucun résultat pour cette recherche.</p>";
  }

  // Fermer la connexion à la base de données
  $conn->close();
}
?>

<style>
.arrow:before {
  content: "↑";
  color: green;
}
</style>
	<?php 
}else{

     header("Location: index.php");

     exit();
}

?>
<!-- Footer -->
<?php include"footer.php"?>
</body>
</html>					