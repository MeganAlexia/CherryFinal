<html lang="fr">
    <head>
        <title>Envoie d'un message</title>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <?php
        $session = session_start();

        $root = "../../";
        require "../../includes.php";

        use Aws\DynamoDb\Exception\DynamoDbException;

// If the member is logged in
        if (isset($_SESSION['email'])) {

            // Constants
            $date = date('Y-m-d H:i:s');

            // If there is a file
            // Add the new element in the database
            try {
                $client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

                $array = array();

                $email = $_SESSION['email'];
                if (!empty($_GET['id'])) {
                    $target = $_GET['target'];
                } else {
                    $target = $_POST['target'];
                }

                $title = htmlentities($_POST['title'], ENT_QUOTES);
                $message = htmlentities($_POST['message'], ENT_QUOTES);


                $array['target'] = array('S' => $target);
                $array['email'] = array('S' => $email);
                $array['id'] = array('S' => md5(uniqid('')));
                $array['date'] = array('S' => $date);
                $array['title'] = array('S' => $title);
                $array['message'] = array('S' => $message);
                $array['new'] = array('S' => "true");
                if (!empty($_GET['id'])) {
                    $replyto = $_GET['id'];
                    $array['replyto'] = array('S' => "$replyto");
                }

                $client->putItem(array(
                    'TableName' => 'Messages',
                    'Item' => $array
                ));


                echo "<br />Message envoyé avec succès. Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/message\"> Envoyer un message </a> ...";
                header('Refresh:2; url=../accueil_adultes.php?page=./Chat/message');
            } catch (DynamoDbException $e) {
                echo 'Exception dynamoDB reçue : ' . $e->getMessage();
                if (!empty($_GET['id'])) {
                    echo 'Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/send&id=' . $_GET['id'] . '\"> Envoyer un message </a> ...';
                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/send&id=' . $_GET['id']);
                } else {

                    echo "<br />Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/send\"> Envoyer un message </a> ...";
                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/send');
                }
            } catch (Exception $e) {
                echo 'Exception reçue : ' . $e->getMessage();
                if (!empty($_GET['id'])) {
                    echo 'Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/send&id=' . $_GET['id'] . '\"> Envoyer un message </a> ...';
                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/send&id=' . $_GET['id']);
                } else {

                    echo "<br />Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/send\"> Envoyer un message </a> ...";
                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/send');
                }
            }
        }
        ?>
    </body>
</html>

