<script src="./FileManage/sorttable.js"></script>
<link href="./FileManage/sorttable.css" rel="stylesheet">
<link href="./FileManage/filemanage.css" rel="stylesheet">


<!-- En-tete-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-laptop"></i>Mes fichiers</h3>
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
            <li><i class="fa fa-laptop"></i>Mes fichiers</li>						  	
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
    ?>
    <form action="file-delete.php" method="post" id="myForm">
        <table class="sortable">
            <thead>
                <tr>
                    <th class="colorwhite">Titre</th>
                    <th class="colorwhite">Nom</th>
                    <th class="colorwhite">Type</th>
                    <th class="colorwhite">Taille <small>(octets)</small></th>
                    <th class="colorwhite">Date d'ajout</th>
                    <th class="colorwhite">Date de début</th>
                    <th class="colorwhite">Date de fin</th>
                    <th class="colorwhite">Enfants destinataires</th>
                    <th class="colorwhite">Description</th>
                    <th class="colorwhite sorttable_nosort">Modifier</th>
                    <th class="colorwhite sorttable_nosort" style="width: 40px;">Supprimer</th> <!-- Delete column-->
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($iterator as $item) {
                    // Prettifies File Types, add more to suit your needs.
                    $extn = strtolower(pathinfo($item['name']['S'], PATHINFO_EXTENSION));
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
                    . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['title']['S'] . '</a></td>'
                    . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $item['realname']['S'] . '</a></td>'
                    . '<td><a href="../Uploads/' . $item['name']['S'] . '">' . $extn . '</a></td>'
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

                    echo '<td><a href="./accueil_adultes.php?page=file-manage-form&id=' . $item["id"]["S"] . '"><img src="./FileManage/edit.png"></a></td><td><input type="checkbox" name="checkDelete[]" value="' . $item["id"]["S"] . '@..' . $item["target"]["S"] . '"/></td></tr>';
                }
                ?>
            </tbody></table>
        <input type="submit" value="Supprimer la sélection">
    </form>
    <?php
} catch (DynamoDbException $e) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}
?>

<script>
    window.onload = function () {
        (document.getElementsByTagName('th')[4]).click(); //Sort the 4th-column by default
    };
</script>