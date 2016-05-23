<?php
echo 'la';

try {
    $client = LocalDBClientBuilder::get();
    $tableName = 'Contents';
    $iterator = $client->getIterator('Scan', array(
        'TableName' => $tableName,
        'ScanFilter' => array(
            'owner' => array(
                'AttributeValueList' =>array(
                    array('S' => $_SESSION['email'])
                ),
                'ComparisonOperator' => 'CONTAINS'
            )
        )
    ));
   
    
    foreach ($iterator as $item) {
        echo 'ici';
        echo $item['title']['S'];
    }

  
} catch (Exception $ex) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}

?>