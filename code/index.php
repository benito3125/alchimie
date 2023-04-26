<!DOCTYPE html>
<html>
    <!-- Inclusion des scripts et liens -->
    <?php include"link.php"?>
    <!-- Navigation -->

<body>
    
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
                <a class="navbar-brand" href="https://benevoles.alchimiedujeu.fr">Bénévoles Alchimie du Jeu</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="login.php">Connexion</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

        
    <!-- Header -->
    <?php include"header.php"?>

    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div>
                    <h2>Bienvenue sur l'outil de gestion des repas des bénévoles du Festival Alchimie du jeu de
                        Toulouse.
                    </h2>
                    <p>Cher public, si tu cherches des informations sur le festival du jeu de Toulouse tu n'es pas au
                        bon endroit, redirige toi vers le site principal :&nbsp;
                        <a href="https://alchimiedujeu.fr/">https://alchimiedujeu.fr/</a>&nbsp;!
                    </p>
                    <p>Par contre si tu es bénévoles, rendez vous sur le site dédié !!
                        <a href="https://benevoles.alchimiedujeu.fr/">https://benevoles.alchimiedujeu.fr/</a>&nbsp;!
                    </p>
                </div>
            </div>
            <!-- Sidebar -->
            <div class="col-sm-4">
                <div class="sidebar">
                    <ul class="sidebar-content">
                        <li class="widget profile">
                            <img src="https://benevoles.alchimiedujeu.fr/themes/batblog/img/profile.jpg" />
                            <h3>
                                Festilouze </h3>
                            <p>
                            </p>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p class="copyright text-muted">
                        Copyright 2023 &copy; Association Alchimie du Jeu.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>