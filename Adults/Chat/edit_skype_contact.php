<html lang="fr">
    <head>
        <title>Ajout d'un contact Skype</title>
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

            // Add the new element in the database
            try {
                $client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

                $tableName = 'Users';

                $response = $client->getItem(array(
                    'TableName' => $tableName,
                    'Key' => array(
                        'email' => array('S' => $_SESSION['email'])
                    )
                ));
                if (!empty($response) && !empty($response['Item'])) {
                    $item = $response['Item'];


                    $array = array();

                    $password = $_SESSION['password'];
                    $firstname = $_SESSION['firstname'];
                    $type = $_SESSION['type'];
                    $email = $_SESSION['email'];
                    $lastname = $_SESSION['lastname'];
                    $contacts = array();
                    $children = array();


                    if ($item['email']['S'] == $email) {
                        // Get the old information
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


                    $array['password'] = array('S' => $password);
                    $array['firstname'] = array('S' => $firstname);
                    $array['type'] = array('S' => $type);
                    $array['email'] = array('S' => $email);
                    $array['lastname'] = array('S' => $lastname);
                    $array['skype'] = array('S' => $skype);

                    //Add the new contact
                    if (isset($_POST['contact'])) {
                        $value = $_POST['contact'];
                        array_push($contacts, "$value");
                    }

                    if (!empty($contacts)) {
                        $array['contacts'] = array('SS' => $contacts);
                    }
                    if (!empty($children)) {
                        $array['children'] = array('SS' => $children);
                    }

                    //...Add anything in the array...

                    $client->putItem(array(
                        'TableName' => 'Users',
                        'Item' => $array
                    ));


                    echo "<div class=\"container\">" .
                    "Contact Skype ajouté avec succès. Redirection vers la page <a href=\"../accueil_adultes.php?page=./Chat/skype_contact\"> Contacts Skype </a> ..." .
                    "</div>";
                    header('Refresh:1; url=../accueil_adultes.php?page=./Chat/skype_contact');
                }
            } catch (DynamoDbException $e) {
                echo 'Exception dynamoDB reçue : ' . $e->getMessage();
            } catch (Exception $e) {
                echo 'Exception reçue : ' . $e->getMessage();
            }
        }
        ?>
    </body>
</html>

