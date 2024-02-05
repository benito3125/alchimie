<!DOCTYPE html>
<html>

    <!-- Inclusion des scripts et liens -->
    <?php include"link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style.css" />
    </head>
    
    <!-- Page Header -->
    <?php include"header.php"?>

    <!-- Navigation -->

<body>
    <?php 
        require_once "resources_acces.php";
        require_once "mysql.php";

        if (isset($_POST['email']) && isset($_POST['password'])) {

            function validate($data){

               $data = trim($data);

               $data = stripslashes($data);

               $data = htmlspecialchars($data);

               return $data;

            }

            $email = validate($_POST['email']);

            $password = validate($_POST['password']);
            $repeat_password = validate($_POST['repeat_password']);
            if (empty($email)) {

                 $message = "L'adresse mail saisie est invalide.";

            }else if(empty($password)){

                //alert('test');
                 $message = "Le mot de passe est incorrect.";

            }else{
                echo "<pre>"; 
                print_r ($_SESSION);
                echo "</pre>";
                if (isset($_SESSION) && isset($_SESSION['user'])){
                    $message = "Vous êtes déjà connecté.";
                    //return null;
                }
                else {
                    $register_status = register($email,$password);
                    $userid = $register_status [0];
                    if ($userid === NULL){
                        $message = $register_status[1];
                    }
                    else {
                        $role = getUserRole($userid);
                        echo $role;
                        
                        $user = new user($userid, $role);
                        $_SESSION ['user'] = $user;
                        echo "<pre>"; 
                        print_r ($_SESSION);
                        echo "</pre>";
                        
                        header("Location: connecte.php");
                        exit();
                    }
                }
            }

        }else{

            //header("Location: connexion.php");

            //exit();

        }
    ?>
    
    <div position="relative">
        <form class="box" action="register.php" method="post" name="register" position="relative">
            <h1 class="box-title">Inscription</h1>
            <input type="email" class="box-input" name="email" placeholder="Adresse mail">
            <input type="password" class="box-input" name="password" placeholder="Mot de passe">
            <input type="password" class="box-input" name="repeat_password" placeholder="Mot de passe">
            <button type="submit" value="Inscription " name="submit" class="box-button">Inscription</button>
            <?php if (! empty($message)) { ?>
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