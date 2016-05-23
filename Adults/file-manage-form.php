<script src="./FileManage/sorttable.js"></script>
<link href="./FileManage/sorttable.css" rel="stylesheet">
<link href="./FileManage/filemanage.css" rel="stylesheet">

<!-- En-tete-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Modification du fichier</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
            <li><i class="fa fa-laptop"></i><a href="./accueil_adultes.php?page=file-manage">Mes fichiers</a></li>
            <li><i class="fa fa-laptop"> Modification du fichier</i></li>						  	
        </ol>
    </div>
</div>



<table class="sortable">
    <thead>
        <tr>
            <th class="sorttable_sorted colorwhite">Titre</th>
            <th class="colorwhite">Nom</th>
            <th class="colorwhite">Type</th>
            <th class="colorwhite">Taille <small>(octets)</small></th>
            <th class="colorwhite">Date d'ajout</th>
            <th class="colorwhite">Date de début</th>
            <th class="colorwhite">Date de fin</th>
            <th class="colorwhite">Enfants destinataires</th>
            <th class="colorwhite">Description</th>

        </tr>
    </thead>
    <tbody>

        <?php
        try {
            //$client = DynamoDbClientBuilder::get();
            $client = LocalDBClientBuilder::get();

            $tableName2 = 'Contents';
            $iterator2 = $client->getIterator('Scan', array(
                'TableName' => $tableName2,
                'ScanFilter' => array(
                    'owner' => array(
                        'AttributeValueList' => array(
                            array('S' => $_SESSION['email'])
                        ),
                        'ComparisonOperator' => 'CONTAINS'
                    ),
                )
            ));
            foreach ($iterator2 as $item2) {

                if ($item2['id']['S'] == $_GET["id"]) {
                    $extn = strtolower(pathinfo($item2['name']['S'], PATHINFO_EXTENSION));
                    switch ($extn) {
                        case "png": $extn = "PNG Image";
                            break;
                        case "jpg": $extn = "JPEG Image";
                            break;
                        case "jpeg": $extn = "JPEG Image";
                            break;
                        case "svg": $extn = "SVG Image";
                            break;
                        case "gif": $extn = "GIF Image";
                            break;
                        case "ico": $extn = "Windows Icon";
                            break;

                        case "txt": $extn = "Text File";
                            break;
                        case "log": $extn = "Log File";
                            break;
                        case "htm": $extn = "HTML File";
                            break;
                        case "php": $extn = "PHP Script";
                            break;
                        case "js": $extn = "Javascript";
                            break;
                        case "css": $extn = "Stylesheet";
                            break;
                        case "pdf": $extn = "PDF Document";
                            break;

                        case "zip": $extn = "ZIP Archive";
                            break;
                        case "bak": $extn = "Backup File";
                            break;

                        default: $extn = strtoupper($extn) . " File";
                            break;
                    }
                    echo '<tr class="fichier">'
                    . '<td><a href="../Uploads/' . $item2['name']['S'] . '">' . $item2['title']['S'] . '</td>'
                    . '<td><a href="../Uploads/' . $item2['name']['S'] . '">' . $item2['realname']['S'] . '</a></td>'
                    . '<td><a href="../Uploads/' . $item2['name']['S'] . '">' . $extn . '</td>'
                    . '<td>' . number_format($item2['length']['N'], 2) . '</td>'
                    . '<td>' . $item2['date']['S'] . '</td>';
                    if (!empty($item2['start']['S'])) {
                        echo '<td>' . $item2['start']['S'] . '</td>';
                    } else {
                        echo '<td>indéterminée</td>';
                    }
                    if (!empty($item2['end']['S'])) {
                        echo '<td>' . $item2['end']['S'] . '</td>';
                    } else {
                        echo '<td>indéterminée</td>';
                    }

                    echo "<td>";

                    echo $item2['target']['S'] . "<br /></td>";
                    if (!empty($item2['description']['S'])) {
                        echo '<td>' . $item2['description']['S'] . '</td>';
                    } else {
                        echo '<td>Vide</td>';
                    }
                }
            }
        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
        ?>
    </tbody></table>
<div class="container">
    <div class="row">
        <form method="post" action="edit-file-manage.php?id=<?php echo $_GET['id']; ?>">

            <fieldset class="form-group">
                <label for="InputTitle">Titre</label>
                <input type="text" name="title" class="form-control" id="InputTitle" placeholder="Entrez un nouveau titre (facultatif)"/>
            </fieldset>

            <fieldset class="form-group">
                <label for="InputDescription">Description</label>
                <textarea name="description" class="form-control" id="InputDescription" placeholder="Entrez une nouvelle description (facultatif)"></textarea>
            </fieldset>

            Destinataire(s) :<br />

            <?php
//Select all the children
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

                // Each item will contain the attributes we added
                echo " <input type='checkbox' onClick='selectAll(this)' onchange='showCalendar()'/> Sélectionner tout<br/>";
                $i = 1;
                $child = array();



                foreach ($iterator as $item) {
                    if ($item['email']['S'] == $_SESSION['email']) {
                        if (!empty($item['children']['SS'])) {
                            $total = count($item['children']['SS']);
                            for ($k = 0; $k < $total; $k++) {
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
                                        echo '<div class="checkbox">';
                                        echo '<input type="checkbox" name="target[]" id="checkbox' . $i . '" value="' . $itemChild['email']['S'] . '" onchange="showCalendar()" > ' . $itemChild['firstname']['S'] . " " . $itemChild['lastname']['S'] . '<br>';
                                        echo '</div>';
                                        $child[$i - 1] = $itemChild['firstname']['S'] . ' ' . $itemChild['lastname']['S'];
                                        $i++;
                                    }
                                }
                            }
                        }
                    }
                }



                $totalCheckbox = $i;
            } catch (DynamoDbException $e) {
                echo "Unable to query:\n";
                echo $e->getMessage() . "\n";
            }
            ?>
            <br />
            <?php
            for ($i = 1; $i < $totalCheckbox; $i++) {
                echo '
        <div id="calendar' . $i . '" style="display:none">
            ----------------------------------------------<br />'
                . $child[$i - 1] . '<br />
            <input type="checkbox" name="checkStart[]" id="isStartChecked' . $i . '" onchange="showDate()"/> Fichier disponible toute la journée <br />

        
        
        
        


    <div id="startSpan' . $i . '"  hidden><input type="text" name="firstdate[]" class="datetimepicker2" placeholder="Date de début" disabled required />
        <input type="text" name="lastdate[]" class="datetimepicker2" placeholder="Date de fin" disabled required/>
        </div>
    <div id="startSpanTime' . $i . '"  ><input type="text" name="firstdateTime[]" class="some_class" placeholder="Date de début" disabled required/>
        <input type="text" name="lastdateTime[]" class="some_class" placeholder="Date de fin" disabled required/>
    </div>
	</div>
           

        ';
            }
            ?>


            <button type="submit" class="btn btn-lg btn-primary btn-block"/>Modifier</button>

        </form>
    </div>
</div>
