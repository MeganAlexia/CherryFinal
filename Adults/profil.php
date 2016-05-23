<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-user-md"></i> Profil</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.php?page=accueil">Accueil</a></li>
            <li><i class="fa fa-user-md"></i>Profil</li>
        </ol>
    </div>
</div>

<div class="row">
    <!-- profile-widget -->
    <div class="col-lg-12">
        <div class="profile-widget profile-widget-info">
            <div class="panel-body">
                <div class="col-lg-2 col-sm-2">
                    <h4><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></h4>     
                    <p><?php echo($_SESSION['type']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- page start-->
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading tab-bg-info">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#profil">
                            <i class="icon-user"></i>
                            Profil
                        </a>
                    </li>

                    <li class="">
                        <a data-toggle="tab" href="#ajout">
                            <i class="icon-envelope"></i>
                            Ajout - Enfants à charge
                        </a>
                    </li>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#suppression">
                            <i class="icon-envelope"></i>
                            Retirer - Enfants à charge
                        </a>
                    </li>

                </ul>
            </header>

            <div class="panel-body">
                <div class="tab-content">                                    
                    <div id="profil" class="tab-pane active">
                        <section class="panel">
                            <div class="panel-body bio-graph-info">
                                <h1>Profil de l'utilisateur</h1>
                                <div class="row">
                                    <div class="bio-row">
                                        <p><span>Prénom </span>: <?php echo $_SESSION['firstname'] ?></p>

                                    </div>
                                    <div class="bio-row">
                                        <p><span>Nom </span>: <?php echo $_SESSION['lastname'] ?></p>
                                    </div>           
                                    <?php
                                    //Select all the children
                                    try {
                                        //$client = DynamoDbClientBuilder::get();
                                        $client = LocalDBClientBuilder::get();
                                        $tableName = 'Users';

                                        $response = $client->getItem(array(
                                            'TableName' => $tableName,
                                            'Key' => array(
                                                'email' => array('S' => $_SESSION['email'])
                                            )
                                        ));
                                        $item = $response['Item'];

                                        $child = array();


                                        if ($item['email']['S'] == $_SESSION['email']) {
                                            echo '<div class="bio-row">'
                                            . '<p><span>Skype</span>: ' . $item['skype']['S'] . '</p>'
                                            . '</div>';
                                            echo '<div class="bio-row">'
                                            . '<p><span>Adresse Mail</span>: ' . $item['email']['S'] . '</p>'
                                            . '</div>';
                                            if (!empty($item['children']['SS'])) {
                                                $total = count($item['children']['SS']);

                                                echo '<div class="bio-row"><p><span>Enfants à charge</span>: ';
                                                for ($k = 0; $k < $total; $k++) {
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
                                                        if ($k != 0) {
                                                            echo '<br /><span></span>&nbsp;&nbsp;';
                                                        }
                                                        echo $itemChild['firstname']['S'] . " " . $itemChild['lastname']['S'];
                                                    }
                                                }
                                                echo '</p></div>';
                                            }
                                        }
                                    } catch (DynamoDbException $e) {
                                        echo "Unable to query:\n";
                                        echo $e->getMessage() . "\n";
                                    }
                                    ?>                                    

                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- edit-profile -->

                    <div id="ajout" class="tab-pane">
                        <section class="panel">                                          
                            <div class="panel-body bio-graph-info">
                                <h1>Ajouter un enfant à ma charge : </h1>
                                <form id="formID" class="form-horizontal" method="POST" 
                                      action="addChild_result.php">
                                          <?php
//Select all the children
                                          try {
                                              //$client = DynamoDbClientBuilder::get();
                                              $client = LocalDBClientBuilder::get();
                                              $tableName = 'Users';
                                              $iterator = $client->getIterator('Scan', array(
                                                  'TableName' => $tableName,
                                                  'ScanFilter' => array(
                                                      'type' => array(
                                                          'AttributeValueList' => array(
                                                              array('S' => 'child')
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
                                                  // Grab the time number value
                                                  echo '<div class="checkbox">';
                                                  echo '<input type="checkbox" name="target[]" id="checkbox' . $i . '" value="' . $item['email']['S'] . '" onchange="showCalendar()" > ' . $item['firstname']['S'] . ' ' . $item['lastname']['S'] . '<br>';
                                                  echo '</div>';
                                                  $child[$i - 1] = $item['firstname']['S'] . ' ' . $item['lastname']['S'];
                                                  $i++;
                                                  // Grab the error string value
                                              }
                                              $totalCheckbox = $i;
                                          } catch (DynamoDbException $e) {
                                              echo "Unable to query:\n";
                                              echo $e->getMessage() . "\n";
                                          }
                                          ?>
                                    <!--
                                    <div class="radios">
                                        <label class="label_radio" for="radio-01">
                                            <input name="sample-radio" id="radio-01" value="1" type="radio" checked /> This is option A...
                                        </label>
        
                                    </div>-->
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            </br>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>                              
                    </div>

                    <div id="suppression" class="tab-pane">
                        <section class="panel">                                          
                            <div class="panel-body bio-graph-info">
                                <h1>Supprimer un enfant à ma charge : </h1>
                                <form id="formID" class="form-horizontal" method="POST" 
                                      action="suppChild_result.php">
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
                                                                      echo '<input type="checkbox" name="checkDelete[]" id="checkbox' . $i . '" value="' . $itemChild['email']['S'] . '" onchange="showCalendar()" > ' . $itemChild['firstname']['S'] . " " . $itemChild['lastname']['S'] . '<br>';
                                                                      echo '</div>';
                                                                      $child[$i - 1] = $item['firstname']['S'] . ' ' . $item['lastname']['S'];
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
                                    <!--
                                    <div class="radios">
                                        <label class="label_radio" for="radio-01">
                                            <input name="sample-radio" id="radio-01" value="1" type="radio" checked /> This is option A...
                                        </label>
                  
                                    </div>-->
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            </br>
                                            <button type="submit" class="btn btn-primary">Supprimer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>


                    </div> <!--div id="suppression"-->

                </div>
            </div>
    </div>
</div>


