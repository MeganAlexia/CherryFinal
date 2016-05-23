<?php
$session = session_start();
//Faire tab pour Children et Adult dans Users
?>

<html lang="fr">
    <head>
        <title>Upload</title>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <?php
        $root = "../";
        require "../includes.php";

        use Aws\DynamoDb\Exception\DynamoDbException;

// Add the new child in the database
        if (isset($_SESSION['email'])) {
            try {
                $client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

                $array = array();

                $k = 0;
                $email = $_SESSION['email'];
                $tableName = "Users";
                $iterator = $client->getIterator('Scan', array(
                    'TableName' => $tableName,
                    'ScanFilter' => array(
                        'email' => array(
                            'AttributeValueList' => array(
                                array('S' => $email)
                            ),
                            'ComparisonOperator' => 'CONTAINS'
                        ),
                    )
                ));

                $children = array();
                $contacts = array();

                $password = $_SESSION['password'];
                $firstname = $_SESSION['firstname'];
                $type = $_SESSION['type'];
                $lastname = $_SESSION['lastname'];

                foreach ($iterator as $item) {
                    if ($item['email']['S'] == $email) {

                        //Get old information
                        $skype = $item['skype']['S'];

                        if (!empty($item['familyId']['S'])) {
                            $familyId = $item['familyId']['S'];
                            $array['familyId'] = array('S' => $familyId);
                        }
                        if (!empty($item['teacherId']['S'])) {
                            $teacherId = $item['teacherId']['S'];
                            $array['teacherId'] = array('S' => $teacherId);
                        }
                        if (!empty($item['doctorId']['S'])) {
                            $doctorId = $item['doctorId']['S'];
                            $array['doctorId'] = array('S' => $doctorId);
                        }

                        if (!empty($item['contacts']['SS'])) {
                            $total = count($item['contacts']['SS']);
                            for ($i = 0; $i < $total; $i++) {
                                $value = $item['contacts']['SS'][$i];
                                array_push($contacts, "$value");
                            }
                        }

                        if (!empty($item['children']['SS'])) {
                            $total2 = count($item['children']['SS']);
                            for ($i = 0; $i < $total2; $i++) {
                                $value2 = $item['children']['SS'][$i];
                                array_push($children, "$value2");
                            }
                        }
                    }
                }

                //Get new info

                if (isset($_POST['checkDelete'])) {
                    if (is_array($_POST['checkDelete'])) {
                        foreach ($_POST['checkDelete'] as $child) {
                            $contactsChild = array();
                            $arraychild = array();
                            $adults = array();
                            $key = array_search($child, $children);
                            if ($key !== false) {
                                unset($children[$key]);                 // remove the key
                                $children = array_values($children); //normalize integer keys
                            }

                            //Get old information of child
                            $oneChild = $child;

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
                                    $passwordchild = $itemChild['password']['S'];
                                    $firstnamechild = $itemChild['firstname']['S'];
                                    $typechild = $itemChild['type']['S'];
                                    $emailchild = $oneChild;
                                    $lastnamechild = $itemChild['lastname']['S'];
                                    $skypechild = $itemChild['skype']['S'];

                                    if (!empty($itemChild['familyId']['S'])) {
                                        $familyId = $itemChild['familyId']['S'];
                                        $arraychild['familyId'] = array('S' => $familyId);
                                    }
                                    if (!empty($itemChild['teacherId']['S'])) {
                                        $teacherId = $itemChild['teacherId']['S'];
                                        $arraychild['teacherId'] = array('S' => $teacherId);
                                    }
                                    if (!empty($itemChild['doctorId']['S'])) {
                                        $doctorId = $itemChild['doctorId']['S'];
                                        $arraychild['doctorId'] = array('S' => $doctorId);
                                    }

                                    if (!empty($itemChild['contacts']['SS'])) {
                                        $total3 = count($itemChild['contacts']['SS']);
                                        for ($i = 0; $i < $total3; $i++) {
                                            $value3 = $itemChild['contacts']['SS'][$i];
                                            array_push($contactsChild, "$value3");
                                        }
                                    }

                                    if (!empty($itemChild['adults']['SS'])) {
                                        $total4 = count($itemChild['adults']['SS']);
                                        for ($i = 0; $i < $total4; $i++) {
                                            $value4 = $itemChild['adults']['SS'][$i];
                                            array_push($adults, "$value4");
                                        }
                                    }
                                }
                            }

                            $arraychild['password'] = array('S' => $passwordchild);
                            $arraychild['firstname'] = array('S' => $firstnamechild);
                            $arraychild['type'] = array('S' => $typechild);
                            $arraychild['email'] = array('S' => $emailchild);
                            $arraychild['lastname'] = array('S' => $lastnamechild);
                            $arraychild['skype'] = array('S' => $skypechild);
                            $key2 = array_search($email, $adults);
                            if ($key2 !== false) {
                                unset($adults[$key2]);                 // remove the key
                                $adults = array_values($adults); //normalize integer keys
                            }

                            if (!empty($contactschild)) {
                                $arraychild['contacts'] = array('SS' => $contactschild);
                            }
                            if (!empty($adults)) {
                                $arraychild['adults'] = array('SS' => array_values(array_unique($adults)));
                            }

                            $client->putItem(array(
                                'TableName' => 'Users',
                                'Item' => $arraychild
                            ));
                        }
                    }
                }

                $array['password'] = array('S' => $password);
                $array['firstname'] = array('S' => $firstname);
                $array['type'] = array('S' => $type);
                $array['email'] = array('S' => $email);
                $array['lastname'] = array('S' => $lastname);
                $array['skype'] = array('S' => $skype);
                if (!empty($contacts)) {
                    $array['contacts'] = array('SS' => $contacts);
                }
                if (!empty($children)) {
                    $array['children'] = array('SS' => array_values(array_unique($children)));
                }

                //...Add anything in the array...

                $client->putItem(array(
                    'TableName' => 'Users',
                    'Item' => $array
                ));


                echo "<div class=\"container\">" .
                "Enfant supprimé avec succès. Redirection vers la page <a href=\"./accueil_adultes.php?page=profil\">Profil</a> ..." .
                "</div>";
                header('Refresh:1; url=./accueil_adultes.php?page=profil');
            } catch (DynamoDbException $e) {
                echo 'Exception dynamoDB reçue : ' . $e->getMessage();
            } catch (Exception $e) {
                echo 'Exception reçue : ' . $e->getMessage();
            }
        }
        ?>
    </body>
</html>

