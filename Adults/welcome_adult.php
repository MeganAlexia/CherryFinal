<script src="./FileManage/sorttable.js"></script>
<link href="./FileManage/sorttable.css" rel="stylesheet">
<link href="./FileManage/filemanage.css" rel="stylesheet">

<!-- En-tete-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Bienvenue <?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>					  	
        </ol>
    </div>
</div>

<?php
$root = "../";
require "../includes.php";

use Aws\DynamoDb\Exception\DynamoDbException;

//Select all the children
try {
    //$client = DynamoDbClientBuilder::get();
    $client = LocalDBClientBuilder::get();
    $tableName = 'Messages';
    $email = $_SESSION['email'];
    $iterator = $client->getIterator('Scan', array(
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

    $i = 1;
    $child = array();
    foreach ($iterator as $item) {
        // Grab the time number value
        if (strcmp($item['new']['S'], "true") == 0) {
            echo '<b>';

            echo 'Nouveau message : <a href="./accueil_adultes.php?page=./Chat/read&id=', $item['id']['S'], '">' . $item['date']['S'], ' - ', html_entity_decode($item['title']['S']), ' [ Message de ', stripslashes(htmlentities(trim($item['email']['S']))), ' ]</a>';

            echo '</b><br />';
        }

        // Grab the error string value
    }

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

    echo 'Evènements en cours : ';
    ?>

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
            foreach ($iterator as $item) {
                if (!empty($item['start']['S']) && !empty($item['end']['S'])) {
                    $startTmp = explode(" ", $item['start']['S']);
                    $start = $startTmp[0];

                    $endTmp = explode(" ", $item['end']['S']);
                    $end = $endTmp[0];

                    $now = date('Y-m-d');
                    if ((strtotime($start) < strtotime($now) || strtotime($start) == strtotime($now)) && (strtotime($end) > strtotime($now) || strtotime($end) == strtotime($now))) {


                        // Prettifies File Types, add more to suit your needs.
                        $extn = pathinfo($item['name']['S'], PATHINFO_EXTENSION);
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

                        // Grab the time number value
                        echo '<tr class="fichier">'
                        . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['title']['S'] . '</td>'
                        . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['realname']['S'] . '</a></td>'
                        . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $extn . '</td>'
                        . '<td>' . number_format($item['length']['N'], 2) . '</td>'
                        . '<td>' . $item['date']['S'] . '</td>';
                        if (!empty($item['start']['S'])) {
                            echo '<td>' . $item['start']['S'] . '</td>';
                        } else {
                            echo '<td>indéterminée</td>';
                        }
                        if (!empty($item['end']['S'])) {
                            echo '<td>' . $item['end']['S'] . '</td>';
                        } else {
                            echo '<td>indéterminée</td>';
                        }

                        echo "<td>";

                        echo $item['target']['S'] . "<br />";

                        echo '</td>';

                        if (!empty($item['description']['S'])) {
                            echo '<td>' . $item['description']['S'] . '</td>';
                        } else {
                            echo '<td>Vide</td>';
                        }
                    }
                }
            }
            ?>
        </tbody></table>

            <?php
            ?>

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
    echo '<br /><br />Evènements à venir :';
    foreach ($iterator as $item) {
        if (!empty($item['start']['S']) && !empty($item['end']['S'])) {

            $startTmp = explode(" ", $item['start']['S']);
            $start = $startTmp[0];

            $endTmp = explode(" ", $item['end']['S']);
            $end = $endTmp[0];

            $now = date('Y-m-d');
            if (strtotime($start) > strtotime($now)) {




                // Prettifies File Types, add more to suit your needs.
                $extn = pathinfo($item['name']['S'], PATHINFO_EXTENSION);
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

                // Grab the time number value
                echo '<tr class="fichier">'
                . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['title']['S'] . '</td>'
                . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['realname']['S'] . '</a></td>'
                . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $extn . '</td>'
                . '<td>' . number_format($item['length']['N'], 2) . '</td>'
                . '<td>' . $item['date']['S'] . '</td>';
                if (!empty($item['start']['S'])) {
                    echo '<td>' . $item['start']['S'] . '</td>';
                } else {
                    echo '<td>indéterminée</td>';
                }
                if (!empty($item['end']['S'])) {
                    echo '<td>' . $item['end']['S'] . '</td>';
                } else {
                    echo '<td>indéterminée</td>';
                }

                echo "<td>";

                echo $item['target']['S'] . "<br />";

                echo '</td>';

                if (!empty($item['description']['S'])) {
                    echo '<td>' . $item['description']['S'] . '</td>';
                } else {
                    echo '<td>Vide</td>';
                }
            }
        }
    }
    ?>
        </tbody></table>
            <?php
        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
        ?>

