<?php
require_once "mysql.php";
?>

<!DOCTYPE html>
<html>
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style.css" />
        <style>
            
            /* Style pour la fenêtre modale */
            .modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.5);
            }
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 600px;
                overflow: auto; /* Ajout de cette ligne pour activer le défilement */
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
            
        <!-- Header -->
        <?php require_once "header.php"?>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div>
                        <!-- Condition pour afficher le texte en fonction du rôle -->
                        <?php if ($userRole === "visiteur"): ?>
                            <h2>Bienvenue sur l'outil de gestion des bénévoles du Festival Alchimie du jeu de Toulouse.</h2>
                            <p>Bravo, tu es maintenant connecté! Un administrateur du site va t'affecter une équipe afin que tu ais accès aux différents outils. Reviens dans quelques temps !</p>
                        <?php else: ?>
                            <h2>Bonjour 😁</h2>
                            <p>Ton équipe est maintenant bien affectée par l'équipe admin.</p>
                            <p>Pour rappel, tu dois respecter les <strong><a href="#" id="cguLink">CGU</a></strong> que tu as validé pour créer ton compte. Ce site est un outil collaboratif dans le but d'optimiser le fonctionnement de l'Alchimie du Jeu. Les données stockées dessus ne doivent en aucun cas être diffusées, <strong>sans accord préalable du comité</strong>. Tout manquement à cette règle entraînera une suppression des accès qui te sont attribués.</p>
                            <p>Pour toutes questions, n'hésite pas à contacter l'équipe admin à <a href="mailto:web@alchimiedujeu.fr">web@alchimiedujeu.fr</a>.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="col-sm-4">
                    <div class="sidebar">
                        <ul class="sidebar-content">
                            <li class="widget profile">
                                <img src="https://benevoles.alchimiedujeu.fr/themes/batblog/img/profile.jpg" />
                                <h3>Festilouze </h3>
                                <p></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!-- Footer -->
        <?php include_once "footer.php"; ?>
        
        <!-- Modal pour les CGU -->
        <div id="cguModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Conditions Générales d'Utilisation</h3>
                <p>Date de dernière mise à jour : 13 février 2024</p>
                <p>Bienvenue sur La Boîte à Outils des Responsables de l'Alchimie !</p>
                <p>Veuillez lire attentivement ces Conditions Générales d'Utilisation ("CGU") avant d'utiliser le site <a href="https://tools.alchimiedujeu.fr">tools.alchimiedujeu.fr</a> exploité par l'Association de l'Alchimie du Jeu, basée à Toulouse    . Votre accès et votre utilisation du Site sont conditionnés par votre acceptation et votre conformité à ces CGU. Ces CGU s'appliquent à tous les visiteurs, utilisateurs et autres personnes qui accèdent ou utilisent le Site.</p>
                <p>En accédant ou en utilisant le Site, vous acceptez d'être lié par ces CGU. Si vous n'acceptez pas une partie ou la totalité de ces termes, vous ne pouvez pas accéder au Site ni l'utiliser.</p>
                <h4>Comptes Utilisateurs</h4>
                <p>Lorsque vous créez un compte avec nous, vous devez fournir des informations exactes, complètes et à jour en tout temps. Le non-respect de cette obligation constitue une violation des CGU, ce qui peut entraîner la résiliation immédiate de votre compte sur notre Site.</p>
                <p>Vous êtes responsable de protéger le mot de passe que vous utilisez pour accéder au Site et des activités ou actions effectuées sous votre mot de passe, que votre mot de passe soit avec notre Site ou avec un service tiers.</p>
                <p>Vous acceptez de ne pas divulguer votre mot de passe à des tiers. Vous devez nous informer immédiatement dès que vous avez connaissance de toute violation de la sécurité ou de toute utilisation non autorisée de votre compte.</p>
                <h4>Contenu</h4>
                <p>Notre Site permet aux utilisateurs de publier, de lier, de stocker, de partager et de rendre accessibles certains types de contenus, y compris, sans limitation, du texte, des graphiques, des vidéos, des liens et d'autres matériaux (le "Contenu"). Vous êtes responsable du Contenu que vous publiez sur le Site, y compris de sa légalité, de sa fiabilité et de sa pertinence.</p>
                <h4>Modifications</h4>
                <p>Nous nous réservons le droit de modifier ces CGU à tout moment. En continuant d'accéder ou d'utiliser notre Site après ces modifications, vous acceptez d'être lié par les CGU révisées.</p>
                <h4>Contact</h4>
                <p>Si vous avez des questions sur ces CGU, veuillez nous contacter à l'adresse email <a href="mailto:web@alchimiedujeu.fr">web@alchimiedujeu.fr</a>.</p>
            </div>
        </div>

        <script>
            // Afficher la fenêtre modale lorsque l'utilisateur clique sur le lien CGU
            const cguLink = document.getElementById('cguLink');
            const cguModal = document.getElementById('cguModal');
            const closeBtn = document.getElementsByClassName('close')[0];

            cguLink.addEventListener('click', function(event) {
                event.preventDefault();
                cguModal.style.display = 'block';
            });

            closeBtn.addEventListener('click', function() {
                cguModal.style.display = 'none';
            });
        </script>
    </body>
</html>
