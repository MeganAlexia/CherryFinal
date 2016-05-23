
<body>
    <a href="accueil_adultes.php?page=./Chat/message">Retour à la boîte de réception</a><br /><br />


    <?php
    try {
        //$client = DynamoDbClientBuilder::get();
        $client = LocalDBClientBuilder::get();



        $tableName = 'Users';
        $iterator = $client->getIterator('Scan', array(
            'TableName' => $tableName,
            'ScanFilter' => array(
                'email' => array(
                    'AttributeValueList' => array(
                        array('S' => $_SESSION['email'])
                    ),
                    'ComparisonOperator' => 'CONTAINS'
                ),
            )
        ));

        $contactsmail = array();
        $contactsname = array();
        foreach ($iterator as $item) {
            if ($item['email']['S'] == $_SESSION['email']) {


                //Get all related children 
                if (!empty($item['children']['SS'])) {
                    $totalchildren = count($item['children']['SS']);
                    for ($k = 0; $k < $totalchildren; $k++) {
                        $oneChild = $item['children']['SS'][$k];

                        $tableName = 'Users';
                        $iteratorChild = $client->getIterator('Scan', array(
                            'TableName' => $tableName,
                            'ScanFilter' => array(
                                'email' => array(
                                    'AttributeValueList' => array(
                                        array('S' => $oneChild)
                                    ),
                                    'ComparisonOperator' => 'CONTAINS'
                                ),
                            )
                        ));

                        foreach ($iteratorChild as $itemChild) {
                            if ($itemChild['email']['S'] == $oneChild) {

                                //Get all related adults 
                                if (!empty($itemChild['adults']['SS'])) {
                                    $total4 = count($itemChild['adults']['SS']);
                                    for ($i = 0; $i < $total4; $i++) {
                                        $adult = $itemChild['adults']['SS'][$i];
                                        $tableName = 'Users';
                                        $iteratorAdult = $client->getIterator('Scan', array(
                                            'TableName' => $tableName,
                                            'ScanFilter' => array(
                                                'email' => array(
                                                    'AttributeValueList' => array(
                                                        array('S' => $adult)
                                                    ),
                                                    'ComparisonOperator' => 'CONTAINS'
                                                ),
                                            )
                                        ));
                                        foreach ($iteratorAdult as $itemAdult) {
                                            if ($itemAdult['email']['S'] == $adult) {
                                                if (!empty($itemAdult['email']['S']) && strcmp($itemAdult['email']['S'], $_SESSION['email']) != 0) {

                                                    $value5 = $itemAdult['email']['S'];
                                                    $value6 = $itemAdult['firstname']['S'] . " " . $itemAdult['lastname']['S'];
                                                    array_push($contactsmail, "$value5");
                                                    array_push($contactsname, "$value6");
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($contactsmail)) {
            $contactsmail = array_values(array_unique($contactsmail));
            $contactsname = array_values(array_unique($contactsname));
            $total = count($contactsmail);
            $target = null;

            if (!empty($_GET['id'])) {

                $tableName = 'Messages';
                $response = $client->getItem(array(
                    'TableName' => $tableName,
                    'Key' => array(
                        'id' => array('S' => $_GET['id'])
                    )
                ));
                if (!empty($response) && !empty($response['Item'])) {
                    $item = $response['Item'];
                    if (strcmp($item['target']['S'], $_SESSION['email']) != 0) {
                        $target = $item['target']['S'];
                    } else {
                        $target = $item['email']['S'];
                    }
                    $title = $item['title']['S'];
                    if (!empty($item['replyto']['S'])) {
                        $replyto_item = $item['replyto']['S'];
                    }


                    $email = $item['email']['S'];

                    $date = $item['date']['S'];
                    $title_old = $item['title']['S'];
                    $message = $item['message']['S'];
                }
            }


            if (empty($target)) {
                ?>
                Envoyer un message :<br /><br />
                <div class="container">
                    <div class="row">
                        <form action="./Chat/edit-send.php" method="post">
                            <fieldset class="form-group">
                                <label for="InputTarget">Pour</label>

                                <select name="target" class="form-control" id="InputTarget">
                                    <?php
                                    for ($i = 0; $i < $total; $i++) {
                                        // on alimente le menu déroulant avec les login des différents membres du site
                                        echo '<option value="' . $contactsmail[$i] . '">' . $contactsname[$i] . '</option>';
                                    }
                                    ?>
                                </select>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="InputTitle">Titre </label><input type="text" name="title" class="form-control" id="InputTitle" placeholder="Entrez un titre" required>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="InputMessage">Message </label><textarea name="message" class="form-control" id="InputMessage" placeholder="Entrez un message" required></textarea> </fieldset><br />
                            <button type="submit" class="btn btn-lg btn-primary btn-block"/>Envoyer</button>
                        </form>

                    </div>
                </div>
                <?php
            } else {
                echo 'Répondre<br /><br />
                                <div class="container">
                                    <div class="row">
                                    <form action="./Chat/edit-send.php?id=' . $_GET['id'] . '&target=' . $target . '" method="post">
                                        <fieldset class="form-group">
                                            <label for="InputTarget">Pour : </label>' . $target . '
                                        </fieldset>
                                    <fieldset class="form-group">
                                        <label for="InputTitle">Titre </label>
                                        <input type="text" name="title" class="form-control" id="InputTitle" value="';
                if (strncmp($title, "RE: ", 4) != 0) {
                    echo 'RE: ';
                }
                echo $title . '" placeholder="Entrez un titre" required>
                                    </fieldset>
                            
                           




                        <fieldset class="form-group">
                            <label for="InputMessage">Message </label><textarea name="message" class="form-control" id="InputMessage" placeholder="Entrez un message" required></textarea> </fieldset><br />
                        <button type="submit" class="btn btn-lg btn-primary btn-block"/>Envoyer</button>
                    </form>
                        
                </div>
            </div>
                <br>';

                echo '<blockquote><font size="3">Le ' . $date . ", " . $email . " a écrit :</font><br><blockquote>" . nl2br(html_entity_decode($message)) . "<br/><br/>";

                if (!empty($replyto_item)) {
                    $oldreplyto = $replyto_item;
                    $replyto = $replyto_item;
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
                            echo '<font size="3">Le ' . $item['date']['S'] . ", " . $item['email']['S'] . " a écrit :</font><blockquote>" . nl2br(html_entity_decode($item['message']['S']));
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
                }
                echo '</blockquote></blockquote>';
            }
            ?>

            <?php
        } else {
            echo 'Aucun autre adulte n\'est associé aux enfants à votre charge.';
        }
    } catch (DynamoDbException $e) {
        echo "Unable to query:\n";
        echo $e->getMessage() . "\n";
    }
    ?>

