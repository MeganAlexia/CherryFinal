<!-- En-tete-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Mes contacts Skype</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
            <li><i class="fa fa-laptop"></i>Mes contacts Skype</li>						  	
        </ol>
    </div>
</div>
<!--
<script type="text/javascript" src="https://secure.skypeassets.com/i/scom/js/skype-uri.js"></script>
<div align="center" style="background-color: #F3F3F3; height:100%; border:1px solid #fff;"><h2><a href="skype:"><img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Skype-icon.svg" height="35"/> Lancer Skype</a></h2></div>

-->
<!-- Récupérer la liste de tous les contacts qui s'occupent du même enfant -->

<?php
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
    $item = $response['Item'];

    $contacts = array();

    if ($item['email']['S'] == $_SESSION['email']) {
        $skypeOwner = $item['skype']['S'];
        //Get the contact list
        if (!empty($item['contacts']['SS'])) {
            $totalContact = count($item['contacts']['SS']);
            for ($i = 0; $i < $totalContact; $i++) {
                $value = $item['contacts']['SS'][$i];
                array_push($contacts, "$value");
            }
        }

        //Get all related children 
        if (!empty($item['children']['SS'])) {
            $totalchildren = count($item['children']['SS']);
            for ($k = 0; $k < $totalchildren; $k++) {
                $oneChild = $item['children']['SS'][$k];

                $tableName = 'Users';

                $responseChild = $client->getItem(array(
                    'TableName' => $tableName,
                    'Key' => array(
                        'email' => array('S' => $oneChild)
                    )
                ));
                $itemChild = $responseChild['Item'];


                if ($itemChild['email']['S'] == $oneChild) {
                    if (!empty($itemChild['skype']['S'])) {

                        $value2 = $itemChild['skype']['S'];
                        array_push($contacts, "$value2");
                    }

                    //Get all related adults 
                    if (!empty($itemChild['adults']['SS'])) {
                        $total4 = count($itemChild['adults']['SS']);
                        for ($i = 0; $i < $total4; $i++) {
                            $adult = $itemChild['adults']['SS'][$i];
                            $tableName = 'Users';

                            $responseAdult = $client->getItem(array(
                                'TableName' => $tableName,
                                'Key' => array(
                                    'email' => array('S' => $adult)
                                )
                            ));
                            $itemAdult = $responseAdult['Item'];

                            if ($itemAdult['email']['S'] == $adult) {
                                if (!empty($itemAdult['skype']['S'])) {

                                    $value5 = $itemAdult['skype']['S'];
                                    if (strcmp($value5, $skypeOwner) != 0) {
                                        array_push($contacts, "$value5");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    $contacts = array_values(array_unique($contacts));
    $total = count($contacts);
} catch (DynamoDbException $e) {
    echo 'Exception dynamoDB reçue : ' . $e->getMessage();
}
?>


<form action="./Chat/edit_skype_contact.php" method="POST">

    <fieldset class="form-group">
        <label for="InputContact">Ajouter un contact Skype</label>
        <input type="text" name="contact" id="InputContact" placeholder="Entrez un pseudo Skype existant" required/>
    </fieldset>

    <button type="submit" class="btn btn-lg btn-primary"/>Envoyer</button>
</form>

<br />
<script type="text/javascript" src="https://secure.skypeassets.com/i/scom/js/skype-uri.js"></script>
<?php
if (!empty($contacts)) {
    for ($i = 0; $i < $total; $i++) {
        echo '<a href="skype:' . $contacts[$i] . '?call"><img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Skype-icon.svg" height="20"/> Appeler ' . $contacts[$i] . '</a><br />';
    }
}
?>


