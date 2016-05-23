<html lang="fr">
    <head>
        <title>Delete</title>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <?php
        $session = session_start();

        $root = "../";
        require "../includes.php";

        use Aws\DynamoDb\Exception\DynamoDbException;

// If the member is logged in

        if (isset($_SESSION['email']) && isset($_POST['checkDelete'])) {

            // Constants
            $target = '../Uploads/';    // Destination
            // Add the new element in the database
            try {
                $client = LocalDBClientBuilder::get();



                $tableName = 'Contents';
                if (isset($_POST['checkDelete'])) {
                    if (is_array($_POST['checkDelete'])) {

                        foreach ($_POST['checkDelete'] as $value) {
                            $tmp = explode("@..", $value);
                            $id = $tmp[0];
                            $child = $tmp[1];
                            $array['target'] = array('S' => $child);
                            $array['id'] = array('S' => $id);

                            //Get the file name
                            $tableName2 = 'Contents';
                            $iterator2 = $client->getIterator('Scan', array(
                                'TableName' => $tableName2,
                                'ScanFilter' => array(
                                    'id' => array(
                                        'AttributeValueList' => array(
                                            array('S' => $id)
                                        ),
                                        'ComparisonOperator' => 'CONTAINS'
                                    ),
                                )
                            ));
                            foreach ($iterator2 as $item2) {
                                if ($item2['owner']['S'] == $_SESSION['email']) {
                                    $name = $item2['name']['S'];
                                }
                            }


                            // Delete the event from the DB
                            $iterator = $client->deleteItem(array(
                                'TableName' => 'Contents',
                                'Key' => $array
                            ));

                            // Look for another event with the same file
                            $tableName3 = 'Contents';
                            $iterator3 = $client->getIterator('Scan', array(
                                'TableName' => $tableName3,
                                'ScanFilter' => array(
                                    'name' => array(
                                        'AttributeValueList' => array(
                                            array('S' => $name)
                                        ),
                                        'ComparisonOperator' => 'CONTAINS'
                                    ),
                                )
                            ));
                            $count = 0;
                            foreach ($iterator3 as $item3) {

                                $count++;
                            }

                            if ($count == 0) {
                                unlink($target . $name);
                                echo "<div class=\"container\">" .
                                "Fichier(s) supprimé(s) avec succès. Redirection vers la <a href=\"./accueil_adultes.php?page=file-manage\"> Mes fichiers </a> ..." .
                                "</div>";
                            } else {
                                echo "<div class=\"container\">" .
                                "Evènement(s) supprimé(s) avec succès. Redirection vers la <a href=\"./accueil_adultes.php?page=file-manage\"> Mes fichiers </a> ..." .
                                "</div>";
                            }
                        }
                    }
                }


                header('Refresh:1; url=./accueil_adultes.php?page=file-manage');
            } catch (DynamoDbException $e) {
                echo "Unable to query:\n";
                echo $e->getMessage() . "\n";
            } catch (Exception $e) {
                echo 'Exception reçue : ' . $e->getMessage();
            }
        } else {
            echo "<div class=\"container\">" .
            "Vous n'avez sélectionné aucun fichier. Redirection vers la page <a href=\"./accueil_adultes.php?page=file-manage\"> Mes fichiers </a> ..." .
            "</div>";
            header('Refresh:1; url=./accueil_adultes.php?page=file-manage');
        }
        ?>
    </body>
</html>

