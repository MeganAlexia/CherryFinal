<html lang="fr">
    <head>
        <title>Modification du fichier</title>
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

            // Add the new element in the database
            try {
                $client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

                $tableName = 'Contents';
                $iterator = $client->getIterator('Scan', array(
                    'TableName' => $tableName,
                    'ScanFilter' => array(
                        'owner' => array(
                            'AttributeValueList' => array(
                                array('S' => $_SESSION['email'])
                            ),
                            'ComparisonOperator' => 'CONTAINS'
                        ),
                    )
                ));

                $array = array();

                $owner = $_SESSION['email'];
                $id = $_GET["id"];

                foreach ($iterator as $item) {
                    if ($item['id']['S'] == $_GET["id"]) {
                        // Get the old information
                        $name = $item['name']['S'];
                        $date = $item['date']['S'];

                        $length = $item['length']['N'];

                        $title = $item['title']['S'];
                        $realname = $item['realname']['S'];
                        $target = $item['target']['S'];

                        if (!empty($item['start']['S'])) {
                            $start = $item['start']['S'];
                        }
                        if (!empty($item['end']['S'])) {
                            $end = $item['end']['S'];
                        }

                        if (!empty($item['description']['S'])) {
                            $description = $item['description']['S'];
                        }
                    }
                }

                $array['id'] = array('S' => $id);
                $array['owner'] = array('S' => $owner);
                $array['name'] = array('S' => $name);

                if (!empty($_POST['title'])) {
                    $title = htmlentities($_POST['title'], ENT_QUOTES);
                }

                if (!empty($_POST['description'])) {
                    $description = htmlentities($_POST['description'], ENT_QUOTES);
                }


                $array['date'] = array('S' => $date);

                $array['length'] = array('N' => $length);

                $array['title'] = array('S' => $title);
                $array['realname'] = array('S' => $realname);

                if (!empty($description)) {
                    $array['description'] = array('S' => $description);
                }

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
                            if ($k != 0 || strcmp($value, $target) != 0) {
                                $id = md5(uniqid(''));
                                $array['id'] = array('S' => $id);
                            }

                            $client->putItem(array(
                                'TableName' => 'Contents',
                                'Item' => $array
                            ));

                            $k++;
                        }
                    }
                } else {
                    $array['target'] = array('S' => $target);

                    if (!empty($start)) {
                        $array['start'] = array('S' => $start);
                    }
                    if (!empty($end)) {
                        $array['end'] = array('S' => $end);
                    }
                    $client->putItem(array(
                        'TableName' => 'Contents',
                        'Item' => $array
                    ));
                }





                //...Add anything in the array...




                echo "<div class=\"container\">" .
                "Fichier modifié avec succès. Redirection vers la page <a href=\"./accueil_adultes.php?page=file-manage\"> Mes fichiers </a> ..." .
                "</div>";
                header('Refresh:1; url=./accueil_adultes.php?page=file-manage');
            } catch (DynamoDbException $e) {
                echo 'Exception dynamoDB reçue : ' . $e->getMessage();
            } catch (Exception $e) {
                echo 'Exception reçue : ' . $e->getMessage();
            }
        }
        ?>
    </body>
</html>

