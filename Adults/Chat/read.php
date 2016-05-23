
<body>
    <a href="accueil_adultes.php?page=./Chat/message">Retour à la boîte de réception</a><br /><br />
    Lire un message :<br />

    <?php
    if (!empty($_GET['id'])) {
        try {
            //$client = DynamoDbClientBuilder::get();
            $client = LocalDBClientBuilder::get();



            $tableName = 'Messages';
            $response = $client->getItem(array(
                'TableName' => $tableName,
                'Key' => array(
                    'id' => array('S' => $_GET['id'])
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



                echo '<font color="black" size="6"><b>' . html_entity_decode($title) . '</b></font><br>' . 'De : ' . $email . "<br>Envoyé le " . $date . '<br><font color="black" size="4">' . nl2br(html_entity_decode($message)) . "</font><br/><br/>";

                if (!empty($item['replyto']['S'])) {
                    echo '<blockquote>';
                    $oldreplyto = $item['replyto']['S'];
                    $replyto = $item['replyto']['S'];
                    $blockquoteNb = 0;
                    while (true) {
                        $tableName = 'Messages';
                        $response = $client->getItem(array(
                            'TableName' => $tableName,
                            'Key' => array(
                                'id' => array('S' => "$replyto")
                            )
                        ));
                        if (!empty($response) && !empty($response['Item'])) {
                            $item = $response['Item'];
                            echo '<font size="3">Le ' . $item['date']['S'] . ", " . $item['email']['S'] . ' a écrit :</font><blockquote><font size="4">' . nl2br(html_entity_decode($item['message']['S'])) . "</font><br/>";
                            if (!empty($item['replyto']['S'])) {
                                $replyto = $item['replyto']['S'];
                            } else {
                                break;
                            }
                            $blockquoteNb++;
                        }
                    }
                    for ($i = 0; $i < $blockquoteNb + 1; $i++) {
                        echo '</blockquote>';
                    }
                    echo '</blockquote>';
                }
            }

            if (strcmp($new, "true") == 0 && strcmp($target, $_SESSION['email']) == 0) {
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
                if (!empty($oldreplyto)) {
                    $array['replyto'] = array('S' => $oldreplyto);
                }

                $client->putItem(array(
                    'TableName' => 'Messages',
                    'Item' => $array
                ));
            }

            echo '<br /><a href="accueil_adultes.php?page=./Chat/send&id=' . $id . '">Répondre</a>';
        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
    }
    ?>

