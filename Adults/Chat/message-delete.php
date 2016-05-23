<html lang="fr">
    <head>
        <title>Suppression de messages</title>
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

            $array = array();
            if (isset($_POST['checkDelete'])) {
                if (is_array($_POST['checkDelete'])) {
                    foreach ($_POST['checkDelete'] as $value) {
                        try {
                            //$client = DynamoDbClientBuilder::get();
                            $client = LocalDBClientBuilder::get();

                            $tableName = 'Messages';
                            $response = $client->getItem(array(
                                'TableName' => $tableName,
                                'Key' => array(
                                    'id' => array('S' => $value)
                                )
                            ));
                            if (!empty($response) && !empty($response['Item'])) {
                                $item = $response['Item'];
                                $target = $item['target']['S'];
                                $email = $item['email']['S'];
                                $id = $item['id']['S'];
                                $date = $item['date']['S'];
                                $title = $item['title']['S'];
                                $message = $item['message']['S'];
                                $new = $item['new']['S'];
                                if (!empty($item['deleteTarget']['S'])) {
                                    $deleteTarget = $item['deleteTarget']['S'];
                                }
                                if (!empty($item['deleteSender']['S'])) {
                                    $deleteSender = $item['deleteSender']['S'];
                                }

                                if (!empty($item['replyto']['S'])) {
                                    $replyto = $item['replyto']['S'];
                                }


                                // If the message (in the inbox) is deleted
                                if (strcmp($_SESSION['email'], $target) == 0) {
                                    $deleteTarget = "true";
                                }
                                // If the message (in the sent box) is deleted
                                else {
                                    $deleteSender = "true";
                                }
                                $otherNb = 1;
                                //If both has already deleted the message 
                                if (!empty($deleteTarget) && !empty($deleteSender)) {
                                    //Check if it is not linked with another "replyto" message
                                    $tableName = 'Messages';
                                    $iterator = $client->getIterator('Scan', array(
                                        'TableName' => $tableName,
                                        'ScanFilter' => array(
                                            'replyto' => array(
                                                'AttributeValueList' => array(
                                                    array('S' => $id)
                                                ),
                                                'ComparisonOperator' => 'CONTAINS'
                                            ),
                                        )
                                    ));
                                    $otherNb = iterator_count($iterator);
                                }




                                // Delete the message from the database
                                if ($otherNb == 0) {
                                    $client->deleteItem(array(
                                        'TableName' => 'Messages',
                                        'Key' => array(
                                            'id' => array('S' => $value)
                                        )
                                    ));
                                    echo "Message(s) supprimé(s) avec succès. Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/message\"> Boîte de réception </a> ...";
                                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/message');
                                } else {
                                    $array = array();
                                    $array['target'] = array('S' => $target);
                                    $array['email'] = array('S' => $email);
                                    $array['id'] = array('S' => $id);
                                    $array['date'] = array('S' => $date);
                                    $array['title'] = array('S' => $title);
                                    $array['message'] = array('S' => $message);
                                    $array['new'] = array('S' => "false");
                                    if (!empty($deleteTarget)) {
                                        $array['deleteTarget'] = array('S' => $deleteTarget);
                                    }
                                    if (!empty($deleteSender)) {
                                        $array['deleteSender'] = array('S' => $deleteSender);
                                    }
                                    if (!empty($replyto)) {
                                        $array['replyto'] = array('S' => $replyto);
                                    }

                                    $client->putItem(array(
                                        'TableName' => 'Messages',
                                        'Item' => $array
                                    ));
                                    echo "Message(s) supprimé(s) avec succès. Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/message\"> Boîte de réception </a> ...";
                                    header('Refresh:2; url=../accueil_adultes.php?page=./Chat/message');
                                }
                            }
                        } catch (DynamoDbException $e) {
                            echo "Unable to query:\n";
                            echo $e->getMessage() . "\n";
                        }
                    }
                }
            } else {
                echo "Aucun message sélectionné. Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/message\"> Boîte de réception </a> ...";
                header('Refresh:2; url=../accueil_adultes.php?page=./Chat/message');
            }
        }
        ?>
    </body>
</html>
