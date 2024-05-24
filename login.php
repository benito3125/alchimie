<!DOCTYPE html>
<html>

<!-- Inclusion des scripts et liens -->
<?php include "link.php"?>

<head>
    <link rel="stylesheet" href="../css/style.css" />
    <script src="https://www.google.com/recaptcha/api.js?render=6LfrT2IpAAAAAGkFfYdJUN266VzhGMXQu4kQ2lQp"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfrT2IpAAAAAGkFfYdJUN266VzhGMXQu4kQ2lQp', { action: 'login' }).then(function(token) {
                document.getElementById('recaptchaResponse').value = token;
            });
        });
    </script>
</head>

<!-- Page Header -->
<?php include "header.php"?>

<!-- Navigation -->

<body>
    <?php 
        //require_once "resources_acces.php";
        require_once "mysql.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $email = validate($_POST['email']);
            $password = validate($_POST['password']);

            // Récupérer le token reCAPTCHA
            $recaptcha_response = $_POST['recaptcha_response'];

            // Vérification côté serveur
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => '6LfrT2IpAAAAAG52uVj-ZxnChRtPADV-XVoQmcjY',
                'response' => $recaptcha_response
            );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $response = json_decode($result);

            if ($response->success) {
                // reCAPTCHA validé
                if (empty($email) || empty($password)) {
                    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                } else {
                    $userid = login($email, $password);
                    if ($userid == NULL) {
                        $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                    } else {
                        $role = getUserRole($userid);
                        $user = new user($userid, $role);
                        $_SESSION['user'] = $user;
                        header("Location: connecte.php");
                        exit();
                    }
                }
            } else {
                // reCAPTCHA invalide
                $message = "Veuillez confirmer que vous n'êtes pas un robot.";
            }
        }
    ?>
    
    <div position="relative">
        <form class="box" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="login" position="relative">
            <h1 class="box-title">Connexion</h1>
            <input type="text" class="box-input" name="email" placeholder="Nom d'utilisateur">
            <input type="password" class="box-input" name="password" placeholder="Mot de passe">
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <button type="submit" value="Connexion " name="submit" class="box-button">Connexion</button>
            <?php if (!empty($message)) { ?>
                <p class="errorMessage">
                    <?php echo $message; ?>
                </p>
            <?php } ?>
        </form>
    </div>
</body>

<!-- Footer -->
<?php include "footer.php" ?>

</html>
