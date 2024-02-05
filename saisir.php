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
		<html>
 

	</head>
    <!-- Navigation -->
    <?php include "nav.php"?>
    <!-- Header -->
    <?php include "header.php"?>
    <?php include "mealsServices.php"?>

<body>

	<div>
		<p>Vous allez scanner les badges afin de vérifier les inscriptions au <BR><CENTER><h2><?php $currentMeal = getCurrentMeal();if (sizeof($currentMeal)==0){echo "Aucun repas sélectioné !";}else{echo $currentMeal[0]["label"];};?></h2><CENTER>.</p>
Vous pouvez changer de repas à l'aide du menu ci-dessous :
<form>
	<label for="listeDeroulante">Repas à vérifier :</label>
	<select id="listeDeroulante" style="background-color: #98d7a5; color: white;">
		<?php 
			$meals = getMeals();
			foreach ($meals as $meal) {
				$label = $meal["label"];
				$code = $meal["code"];
				echo "<option value=\"$code\">$label</option>";
			}
		?>
	</select>
	<br><br>
	<input id="launchButton" type="button" value="Sélectionner ce repas" onclick="setCurrentMeal()">
</form>

<BR>

<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	include "config.php";
	if(isset($_SESSION["username"])){ 
		// Vérification de la connexion
		/*if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		} else {
		echo "Connexion réussie";
		}*/
?>
		<div id="resultat"></div>
		<h1>Scanner de QR code</h1>
		<div id="scanner">
			<video id="preview"></video>
			<canvas id="qr-canvas"></canvas>
		</div>
		<div>
			<button id="launchButton" onclick="startScanner()">Démarrer le scanner</button>
		</div>
		<div id="result"></div>
		<script>
			function setCurrentMeal() {
                                 //alert("on set le curent meal!");
				var choix = document.getElementById("listeDeroulante").value;
				//alert("Vous avez choisi : " + choix);
                                var request = $.ajax({
							url: "updateCurrentMealQuery.php", // URL du script PHP pour la requête SQL
							method: "POST",
							dataType: "text",
							data: {mealCode: choix}
							});
						
                               //definition de la callback success de requete ajax query
                               request.done(function(data) 
                               {
                                  //       alert('La fonctionupdate CurrentMeal a reussi :-)');
                                         location.reload();
                               });
                               request.fail(function(data) {
                                         //alert('La fonctionupdate CurrentMeala échoué :-(');
                                          location.reload();
                               });
                             
			}

			document.getElementById('launchButton').style.backgroundColor = "orange";
			document.getElementById('launchButton').style.color = "white";
			document.getElementById('launchButton').style.borderRadius = "10px";
			document.getElementById('launchButton').style.padding = "10px 20px";
			document.getElementById('launchButton').style.border = "none";
			document.getElementById('launchButton').style.cursor = "pointer";


			function startScanner() {
                                //alert('ca scanne !');
				// Créer une instance du scanner
				let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
				
				//ajout du listener
				
				scanner.addListener('scan', function(content) {
						// Récupérer l'ID du QR code scanné
						let scannedId = "'"+content+"'";
						//alert("on a scanne l'id "+scannedId);
						var request = $.ajax({
							url: "checkGuestQuery.php", // URL du script PHP pour la requête SQL
							method: "POST",
							dataType: "json",
							data: {scannedId: scannedId}
							});
						
						
						//definition de la callback success de requete ajax query
						request.done( function(data) {
							//alert('hello ajax ok !');
                                                        //alert(JSON.stringify(data));
							let result = JSON.parse(JSON.stringify(data));
							//alert('json parsé');
                                                        let mealValue = result.<?php $currentMeal = getCurrentMeal();echo $currentMeal[0]["code"];?>;
							//alert('value pour le repa en cours='+mealValue);
							//alert('value pour le repa en cours='+nameValue);
							let nameValue = result.first_name;
							let vegeValue = result.vege;
							//document.getElementById('name').textContent = nameValue;
							//alert('vege value ='+vegeValue);
							if (mealValue == 1) {
								// Si la valeur du repas est égale à 1 (=repas OK)
								if (vegeValue == 1) {
								// Si la valeur végé est égale à 1
								document.body.style.backgroundColor = "green";
								document.getElementById('result').textContent = "Repas validé (végé) pour";
								} else {
								// Si la valeur végé est égale à 0
								document.body.style.backgroundColor = "orange";
								document.getElementById('result').textContent = "Repas validé pour";
								}

								// Passer la valeur du repas  à 2 dans la base de données
								var updateRequest = $.ajax({
									method: "POST",
									url: "acceptGuestQuery.php",
									data: {id: scannedId},
                                                                        });
								updateRequest.done(function(response) 
                                                                        {
                                                                                //alert("la requete d'update a reussi");
										// Afficher la réponse de la requête d'update
										console.log(response);
									});
                                                                updateRequest.fail(function(xhr, status, error) 
                                                                         {
                                                                                //alert("echec de la requete d'update");
										console.error(error);
									});

							} else if (mealValue == 0) {
								// Si la valeur du repas est égale à 0 (=pas de repas)
								document.body.style.backgroundColor = "red";
								document.getElementById('result').textContent = "Pas de repas prévu pour";
							} else {
								// Si la valeur RJM est égale à 2 (=deja passé)
								document.body.style.backgroundColor = "red";
								document.getElementById('result').textContent = "Repas déjà validé pour";
							}

							document.getElementById('result').style.color = "white";
							document.getElementById('result').innerHTML += "<br><strong>" + nameValue + "</strong>";

						});
						
						//fin de la callback success
						
						//definition de la callback error de la requete ajax query
						request.fail( function(xhr, status, error) {
							//alert("La requête s'est terminée en échec. Infos : " + JSON.stringify(error));
							console.log("Une erreur s'est produite lors de la requête Ajax: " + status + " " + error);
						}
						);
						//fin de la callback error
				
				});
				
				//fin de l'ajout du listener
				
				// Démarrer le scanner
				Instascan.Camera.getCameras().then(function(cameras) {
					if (cameras.length > 1) 
					{
						scanner.start(cameras[1]);
					} else 
					{
						scanner.start(cameras[0]);
					}
				}).catch(function (e) {
					console.error(e);
				});
				//fin du démarrage du scanner
			};
		</script>
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