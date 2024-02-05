<!DOCTYPE html>
<html>

    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <head>
        <link rel="stylesheet" href="../css/style.css" />
    </head>
    
    <!-- Page Header -->
    <?php include "header.php"?>

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
            echo "test";
            if (empty($email)) {
                $message = "Le nom d'utilisateur est incorrect.";

            }else if(empty($password)){

                //alert('test');
                 $message = "Le mot de passe est incorrect.";

            }else{
                $userid = login($email,$password);
                if ($userid == NULL){
                    $message = "Le mot de passe est incorrect.";
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
        }else{
        }
    ?>
    
    <div position="relative">
        <form class="box" action="login.php" method="post" name="login" position="relative">
            <h1 class="box-title">Connexion</h1>
            <input type="text" class="box-input" name="email" placeholder="Nom d'utilisateur">
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
<?php include "footer.php" ?>


</html>