<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Ajouter un fichier</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
            <li><i class="fa fa-laptop"></i>Ajouter un fichier</li>						  	
        </ol>
    </div>
</div>


<div class="container">
    <div class="row">
        <form method="post" action="upload-target.php" enctype="multipart/form-data" novalidate class="box">

            Fichier(s) à envoyer :
            <div id="drop-zone">
                <br /><p>Glissez les fichiers ici...</p>
                <div id="clickHere">
                    ou cliquez ici...<i class="fa fa-upload"></i>
                    <input type="file" name="file[]" id="file"  multiple="multiple" />
                </div>
                <div id='filename'></div>
            </div>  
            <br />

            <fieldset class="form-group">
                <label for="InputTitle">Titre</label>
                <input type="text" name="title" class="form-control" id="InputTitle" placeholder="Entrez un titre" required/>
            </fieldset>

            <fieldset class="form-group">
                <label for="InputDescription">Description</label>
                <textarea name="description" class="form-control" id="InputDescription" placeholder="Entrez une description"></textarea>
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

            <br><br>
            <button type="submit" class="btn btn-lg btn-primary btn-block"/>Envoyer</button>

        </form>
    </div>
</div>
<p id="demo"></p>










