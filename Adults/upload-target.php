<html lang="fr">
    <head>
        <title>Upload</title>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <?php
        $session = session_start();

        $root = "../";
        require "../includes.php";

        use Aws\DynamoDb\Exception\DynamoDbException;

// If the member is logged in
        if (isset($_SESSION['email'])) {

            // Constants
            $target = '../Uploads/';    // Destination
            $infosFile = array();
            $date = date('Y-m-d H:i:s');

            // If there is a file
            if (!empty($_FILES['file']['name'])) {
                // Count # of uploaded files in array
                $total = count($_FILES['file']['name']);
                $error = 0;
                for ($i = 0; $i < $total; $i++) {
                    // Take the extension info
                    $extension = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
                    $length = filesize($_FILES['file']['tmp_name'][$i]); //in Bytes
                    // Check if there is an error
                    if (isset($_FILES['file']['error'][$i]) && UPLOAD_ERR_OK === $_FILES['file']['error'][$i]) {
                        // Rename the file
                        $realname = basename($_FILES['file']['name'][$i]);
                        $name = md5(uniqid('')) . '.' . $extension;
                        // Upload
                        if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target . $name)) {

                            // Add the new element in the database
                            try {
                                $client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

                                $array = array();

                                $owner = $_SESSION['email'];
                                $title = htmlentities($_POST['title'], ENT_QUOTES);
                                if (isset($_POST['description'])) {
                                    $description = htmlentities($_POST['description'], ENT_QUOTES);
                                } else {
                                    $description = "";
                                }

                                $array['name'] = array('S' => $name);
                                $array['owner'] = array('S' => $owner);
                                $array['realname'] = array('S' => $realname);
                                $array['date'] = array('S' => $date);
                                $array['length'] = array('N' => $length);
                                $array['title'] = array('S' => $title);
                                $array['description'] = array('S' => $description);

                                $k = 0;
                                if (isset($_POST['target'])) {
                                    if (is_array($_POST['target'])) {
                                        foreach ($_POST['target'] as $value) {

                                            if (!isset($_POST['checkStart'][$k])) {
                                                if (!empty($_POST['firstdateTime'][$k])) {

                                                    $dateFirstTmp = explode(" ", $_POST['firstdateTime'][$k]);
                                                    $firsthour = $dateFirstTmp[1];
                                                    $firstdate = explode("/", $dateFirstTmp[0]);

                                                    $yearStart = $firstdate[0];
                                                    $monthStart = $firstdate[1];
                                                    $dayStart = $firstdate[2];

                                                    $start = $yearStart . "-" . $monthStart . "-" . $dayStart . " " . $firsthour . ":00";
                                                    $array['start'] = array('S' => $start);

                                                    $dateLastTmp = explode(" ", $_POST['lastdateTime'][$k]);
                                                    $lasthour = $dateLastTmp[1];
                                                    $lastdate = explode("/", $dateLastTmp[0]);

                                                    $yearEnd = $lastdate[0];
                                                    $monthEnd = $lastdate[1];
                                                    $dayEnd = $lastdate[2];

                                                    $end = $yearEnd . "-" . $monthEnd . "-" . $dayEnd . " " . $lasthour . ":00";
                                                    $array['end'] = array('S' => $end);
                                                }
                                            } else {
                                                //All day
                                                if (!empty($_POST['firstdate'][$k])) {
                                                    $firstdate = explode("/", $_POST['firstdate'][$k]);


                                                    $dayStart = $firstdate[0];
                                                    $monthStart = $firstdate[1];
                                                    $yearStart = $firstdate[2];
                                                    $start = $yearStart . "-" . $monthStart . "-" . $dayStart;
                                                    $array['start'] = array('S' => $start);

                                                    $lastdate = explode("/", $_POST['lastdate'][$k]);


                                                    $dayEnd = $lastdate[0];
                                                    $monthEnd = $lastdate[1];
                                                    $yearEnd = $lastdate[2];
                                                    $end = $yearEnd . "-" . $monthEnd . "-" . $dayEnd;
                                                    $array['end'] = array('S' => $end);
                                                }
                                            }



                                            $array['target'] = array('S' => $value);
                                            $id = md5(uniqid(''));
                                            $array['id'] = array('S' => $id);
                                            $client->putItem(array(
                                                'TableName' => 'Contents',
                                                'Item' => $array
                                            ));

                                            $k++;
                                        }
                                    }
                                }
                            } catch (DynamoDbException $e) {
                                $error++;
                                echo 'Exception dynamoDB reçue : ' . $e->getMessage();
                                echo "<br />Redirection vers la page <a href=\"./accueil_adultes.php?page=add\"> Ajouter un fichier </a> ...";
                                header('Refresh:2; url=./accueil_adultes.php?page=add');
                            } catch (Exception $e) {
                                $error++;
                                echo 'Exception reçue : ' . $e->getMessage();
                                echo "<br />Redirection vers la page <a href=\"./accueil_adultes.php?page=add\"> Ajouter un fichier </a> ...";
                                header('Refresh:2; url=./accueil_adultes.php?page=add');
                            }
                        } else {
                            $error++;
                            echo "ERREUR : Problème lors du déplacement de votre fichier dans notre site !";
                            echo "<br />Redirection vers la page <a href=\"./accueil_adultes.php?page=add\"> Ajouter un fichier </a> ...";
                            header('Refresh:2; url=./accueil_adultes.php?page=add');
                        }
                    } else {
                        $error++;
                        echo "ERREUR : Une erreur interne a empêché l'upload du fichier";
                        echo "<br />Redirection vers la page <a href=\"./accueil_adultes.php?page=add\"> Ajouter un fichier </a> ...";
                        header('Refresh:2; url=./accueil_adultes.php?page=add');
                    }
                }
                if ($error == 0) {
                    echo "<div class=\"container\">" .
                    "Fichier(s) envoyé(s) avec succès. Redirection vers la page <a href=\"./accueil_adultes.php?page=file-manage\"> Mes fichiers </a> ..." .
                    "</div>";
                    header('Refresh:1; url=./accueil_adultes.php?page=file-manage');
                }
            } else {

                echo"ERREUR : Aucun fichier sélectionné.";
                echo "<br />Redirection vers la page <a href=\"./accueil_adultes.php?page=add\"> Ajouter un fichier </a> ...";
                header('Refresh:2; url=./accueil_adultes.php?page=add');
            }
        }
        ?>
    </body>
</html>

