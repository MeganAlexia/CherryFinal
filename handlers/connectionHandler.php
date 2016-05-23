<?php 
// Désactiver le rapport d'erreurs
    //error_reporting(0);
    session_start();
?>

<!doctype html>
<html>
    
<head>
    <meta charset="utf-8">
    <title>Connection</title>
    <?php
        $root = "../";
        require "../includes.php";
        use Aws\DynamoDb\Exception\DynamoDbException;
        ?>
</head>

<body>
    <!-- <a href="../temp.php">temp.php</a> -->
    <?php
     
    // ligne pour tester le calendrier
    // header('Location: ../calendar/calendar.php');

    try {
        //$client = DynamoDbClientBuilder::get();
        $client = LocalDBClientBuilder::get();
        
        if(isset($_POST['email'])){
            $email = $_POST['email'];
        }
        if(isset($_POST['password'])){
            $password = md5($_POST['password']);
        }
        

        $dao = new UserDAO($client);
        //PROBLEM
        $user = $dao->get($email);
        
        
        if ($user != null) {
            if ($user->getPassword() == $password) {
                $_SESSION['email'] = $email;
                $firstname = $user->getFirstname();
                $_SESSION['firstname'] = $firstname;
                $lastname = $user->getLastname();
                $_SESSION['lastname'] = $lastname;
                $type = $user->getType();
                $_SESSION['type']  = $type; 
                $password = $user->getPassword();
                $_SESSION['password']  = $password; 
                if ($type == "child") {
                    //header('Location: ../childShowContents.php?type=doctor');
                    header('Location: ../room.php');
                }
                else{
                    header('Location: ../Adults/accueil_adultes.php?page=welcome_adult');
                }
            } else {
                session_destroy();
                require_once("../login/login.php");
                //echo "Mot de passe incorrect.";
            }
        } else {
            session_destroy();
            echo "L'utilisateur n'existe pas.";
           // require_once("../login/login.php");
        }
    } catch (DynamoDbException $e) {
        echo '<p>Exception dynamoDB reçue : ',  $e->getMessage(), "\n</p>";
    } catch (Exception $e) {
        echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
    }
    
    /*
    
    //TEST -----------------------------------------------------------------------------------------------------------
    try {
        //$client = DynamoDbClientBuilder::get();
        $client = LocalDBClientBuilder::get();
        $email = $_POST['email'];
        $password = $_POST['password'];

        $dao = new UserDAO($client);
        $user = $dao->get($email);
        if ($user != null) {
            if ($user->getPassword() == $password) {
                $_SESSION['email'] = $email;
                $type = $user->getType();
                $_SESSION['type']  = $type; 
                if ($type == "child") {
                    //header('Location: ../childShowContents.php?type=doctor');
                    header('Location: ../room.php');
                } else if ($type == "teacher") {
                    header('Location: ../adultShowContents.php');
                } else if ($type == "doctor") {
                    header('Location: ../adultShowContents.php');
                } else if ($type == "family") {
                    header('Location: ../adultShowContents.php');
                }
            } else {
                session_destroy();
                echo "Mot de passe incorrect.";
            }
        } else {
            session_destroy();
            echo "L'utilisateur n'existe pas.";
        }
    } catch (DynamoDbException $e) {
        echo '<p>Exception dynamoDB 1 reçue : ',  $e->getMessage(), "\n</p>";
    } catch (Exception $e) {
        echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
    }
     
    //TEST END -----------------------------------------------------------------------------------------------
    */
    ?>
      
     
</body>
</html>





