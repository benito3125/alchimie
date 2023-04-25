<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Repas des Bénévoles">
    <meta name="keywords" content="repas, jeu, festival, toulouse, alchimie, bénévoles">
    <title>Repas des Bénévoles</title>
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/themes/batblog/css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/56fa629212.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/bootstrap.min.js"></script>
    <script src="https://benevoles.alchimiedujeu.fr/themes/batblog/js/theme.js"></script>
    <meta name="generator" content="Batflat" />
    <link rel="stylesheet" href="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.css">
    <script src="https://benevoles.alchimiedujeu.fr/inc/jscripts/lightbox/lightbox.min.js"></script>
</head>


<!-- Navigation -->
<nav class="navbar navbar-default navbar-custom">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Header -->
<header class="intro-header"
    style="background-image: url('https://benevoles.alchimiedujeu.fr/themes/batblog/img/default-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>Page de connexion</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<body>
    <?php 

        session_start(); 

        include "config.php";

        if (isset($_POST['username']) && isset($_POST['password'])) {

            function validate($data){

               $data = trim($data);

               $data = stripslashes($data);

               $data = htmlspecialchars($data);

               return $data;

            }

            $username = validate($_POST['username']);

            $password = validate($_POST['password']);
            if (empty($username)) {

                 $message = "Le nom d'utilisateur est incorrect.";

            }else if(empty($password)){

                 $message = "Le mot de passe est incorrect.";

            }else{

                $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

                $result = mysqli_query($conn, $sql);


                if (mysqli_num_rows($result) === 1) {

                    $row = mysqli_fetch_assoc($result);

                    if ($row['username'] === $username && $row['password'] === $password) {

                        echo "Logged in!";
                    
                        $_SESSION['username'] = $row['username'];


                        header("Location: connect.php");

                        exit();

                    }else{

                         $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";

                    }

                }else{

                    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                }

            }

        }else{

            //header("Location: connexion.php");

            //exit();

        }
    ?>
    
    <div position="relative">
        <form class="box" action="connexion.php" method="post" name="login" position="relative">
            <h1 class="box-title">Connexion</h1>
            <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">
            <input type="password" class="box-input" name="password" placeholder="Mot de passe">
            <button type="submit" value="Connexion " name="submit" class="box-button">Connexion</button>
            <?php if (! empty($message)) { ?>
                <p class="errorMessage">
                <?php echo $message; ?>
                </p>
            <?php } ?>
        </form>
    </div>
</body>


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


</html>
