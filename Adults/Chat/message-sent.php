<script src="./FileManage/sorttable.js"></script>
<link href="./FileManage/sorttable.css" rel="stylesheet">

<!-- En-tete-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Boîte de réception</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
            <li><i class="fa fa-laptop"></i>Boîte de réception</li>						  	
        </ol>
    </div>
</div>




<?php
try {
    //$client = DynamoDbClientBuilder::get();
    $client = LocalDBClientBuilder::get();
    $tableName = 'Messages';
    $email = $_SESSION['email'];
    $iteratorNewNb = $client->getIterator('Scan', array(
        'TableName' => $tableName,
        'ScanFilter' => array(
            'target' => array(
                'AttributeValueList' => array(
                    array('S' => $email)
                ),
                'ComparisonOperator' => 'CONTAINS'
            ),
        )
    ));
    $totalNew = 0;
    foreach ($iteratorNewNb as $item) {

        if (strcmp($item['new']['S'], "true") == 0) {
            $totalNew++;
        }
    }

    $iteratorTmp = $client->getIterator('Scan', array(
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


    $total = iterator_count($iteratorTmp);

    $numberPerPage = 50; //Pages Number per page
    if ($total < $numberPerPage) {
        $numberPerPage = $total;
    }
    if ($total != 0) {
        $pageNumber = ceil($total / $numberPerPage);
    } else {
        $pageNumber = 0;
    }
    // Order by date
    $iterator = array();
    $iterator = $iteratorTmp->toArray();

    function date_compare($a, $b) {
        $t1 = strtotime($a['date']['S']);
        $t2 = strtotime($b['date']['S']);
        return $t2 - $t1;
    }

    usort($iterator, 'date_compare');
    ?>

    <form action="Chat/message-delete.php" method="POST">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-2">
                    <div class="btn-group">
                    </div>
                </div>
                <div class="col-sm-9 col-md-10">
                    <!-- Split button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">
                            <div class="checkbox" style="margin: 0;">
                                <label>
                                    <input type="checkbox" onClick='selectAll(this)' />
                                </label>
                            </div>
                        </button>

                    </div>
                    <button type="button" class="btn btn-default" data-toggle="tooltip" title="Actualiser" onClick="window.location.reload()">
                        &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;</button>
                    <!-- Single button -->

                    <button type="submit" class="btn btn-default"  title="Supprimer la sélection" >
                        &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;&nbsp;</button>


                    <div class="pull-right">
                        <span class="text-muted"><b><span id="pageNumber">1</span></b>–<b><span id="pageNumberEnd"><?php echo $numberPerPage; ?></span></b> of <b><?php echo $total; ?></b></span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-default previous">
                                <span class="glyphicon glyphicon-chevron-left "></span>
                            </button>
                            <button type="button" class="btn btn-default next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-3 col-md-2">
                    <a href="accueil_adultes.php?page=./Chat/send" class="btn btn-danger btn-sm btn-block" role="button"><i class="glyphicon glyphicon-edit"></i> NOUVEAU MESSAGE</a>
                    <hr>
                    <ul class="nav nav-pills nav-stacked">
                        <li class=""><a href="accueil_adultes.php?page=./Chat/message"><?php if ($totalNew != 0) {
        echo '<span class="badge pull-right">' . $totalNew . '</span>';
    } ?> Boîte de réception </a>
                        </li>
                        <li class="active"><a href="accueil_adultes.php?page=./Chat/message-sent">Messages envoyés</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-md-10">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab"><span class="glyphicon glyphicon-inbox">
                                </span>Envoyés</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active">
                            <div class="list-group">
                                <p style="display: none">mytext1</p>

                                <?php
                                $itemKey = 1;
                                foreach ($iterator as $item) {
                                    if (empty($item['deleteSender']['S'])) {
                                        if ($itemKey == 1) {
                                            echo '<div class="form-panel active">';
                                        } else if (($itemKey % $numberPerPage) == 1 && $itemKey != 1) {
                                            echo '<div class="form-panel">';
                                        }
                                        $message_extract = explode(" ", html_entity_decode($item['message']['S']));
                                        ?>
                                        <a href="./accueil_adultes.php?page=./Chat/read&id=<?php echo $item['id']['S']; ?>" class="list-group-item read">


                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="checkbox1" name="checkDelete[]" value="<?php echo $item["id"]["S"]; ?>">
                                                </label>
                                            </div>
                                            <span class="name" style="min-width: 120px;
                                                  display: inline-block;">À : <?php echo stripslashes(htmlentities(trim($item['target']['S']))); ?></span> <span class=""><?php echo html_entity_decode($item['title']['S']); ?></span>
                                            <span class="text-muted" style="font-size: 11px;">- <?php for ($i = 0; $i < 10; $i++)
                                if (!empty($message_extract[$i])) echo $message_extract[$i] . ' '; ?></span> <span class="badge"><?php echo $item['date']['S']; ?></span> <span class="pull-right">
                                            </span></a>



            <?php
            if ((($itemKey % $numberPerPage) == 0) || ($itemKey == $total)) {
                echo '</div>';
            }
            $itemKey++;
        }
    }
    ?>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </form>
    <?php
} catch (DynamoDbException $e) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}
?>



<script type="text/javascript">
    var pageNumber = <?php echo $pageNumber; ?>;
    var numberPerPage = <?php echo $numberPerPage; ?>;
    var totalMessage = <?php echo $total; ?>;
</script>

