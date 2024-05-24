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

    <?php include "header.php"?>

    <body>
        <?php 
            require_once "mysql.php";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                function validate($data){
                   $data = trim($data);
                   $data = stripslashes($data);
                   $data = htmlspecialchars($data);
                   return $data;
                }
                $email = validate($_POST['email']);
                $password = validate($_POST['password']);
                $repeat_password = validate($_POST['repeat_password']);
                $first_name = validate($_POST['first_name']);
                $family_name = validate($_POST['family_name']);
                if (empty($first_name)){
                    $message = "Le prénom est requis.";
                }
                if (empty($family_name)){
                    $message = "Le nom de famille est requis.";
                }
                if (empty($email)) {
                    $message = "L'adresse mail saisie est invalide.";
                } elseif (empty($password)) {
                    $message = "Le mot de passe est incorrect.";
                } elseif ($password != $repeat_password) {
                    $message = "Les mots de passe ne correspondent pas.";
                } else {
                    if (isset($_SESSION) && isset($_SESSION['user'])){
                        $message = "Vous êtes déjà connecté.";
                    } else {
                        $register_status = register($email, $password, $first_name, $family_name);
                        $userid = $register_status[0];
                        if ($userid === NULL) {
                            $message = $register_status[1];
                        } else {
                            $role = getUserRole($userid);
                            $user = new user($userid, $role);
                            $_SESSION['user'] = $user;
                            header("Location: connecte.php");
                            exit();
                        }
                    }
                }
            }
        ?>

        <div position="relative">
            <form class="box" action="register.php" method="post" name="register" position="relative">
                <h1 class="box-title">Inscription</h1>
                <input type="text" class="box-input" name="first_name" placeholder="Prénom">
                <input type="text" class="box-input" name="family_name" placeholder="Nom">
                <input type="email" class="box-input" name="email" placeholder="Adresse mail">
                <input type="password" class="box-input" name="password" placeholder="Mot de passe">
                <input type="password" class="box-input" name="repeat_password" placeholder="Confirmez le mot de passe">
                <input type="checkbox" id="cguCheckbox" name="cguCheckbox">
                <label for="cguCheckbox">J'ai lu les <a href="#" id="cguLink">CGU</a> avant de m'inscrire</label>
                <button type="submit" value="Inscription" name="submit" class="box-button">Inscription</button>
                <?php if (!empty($message)) { ?>
                    <p class="errorMessage">
                        <?php echo $message; ?>
                    </p>
                <?php } ?>
            </form>
        </div>

        <!-- Fenêtre modale pour afficher les CGU -->
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


        <!-- Script JavaScript -->
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

            // Vérifier si la case à cocher est cochée avant de soumettre le formulaire
            const cguCheckbox = document.getElementById('cguCheckbox');
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!cguCheckbox.checked) {
                    alert("Veuillez lire les CGU et cocher la case avant de vous inscrire.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                }
            });
        </script>

        <?php include "footer.php" ?>
    </body>
</html>
