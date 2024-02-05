<!DOCTYPE html>
<html>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <!-- Navigation -->

<body>
            
    <!-- Header -->
    <?php include "header.php"?>

    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div>
                    <h2>L'espace statistiques ? C'est ici !
                    </h2>
                    <p>Sur cette page tu as la possibilité d'accéder à l'ensemble des statistiques du festival.&nbsp;
            Les données sont mises automatiquement à jour, sauf pour le total des visiteurs.
            Si jamais tu constates des erreurs, n'hésites pas à nous le faire savoir en contactant l'équipe Accueil sur Discord 😊.
                    </p>
                </div>
            </div>
        </div>
        <!-- Boutons -->
        <div class="row mt-3">
            <div class="col-sm-12 text-center">
                <a href="stats_repas.php" class="btn btn-orange btn-lg mr-3">Repas</a>
                <a href="stats_fournitures.php" class="btn btn-gray btn-lg">Fournitures</a>
                <a href="stats_visiteurs.php" class="btn btn-gray btn-lg ml-3">Bouton 3</a>
            </div>
        </div>
    </div>
    <hr>
    <!-- Footer -->
<?include "footer.php" ?>
</body>
</html>
