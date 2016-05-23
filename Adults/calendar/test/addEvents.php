<?php

$root = "../New/"; 
require "../New/includes.php";

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
 
$client = LocalDBClientBuilder::get();
$marshaler = new Marshaler();
$json = file_get_contents('test.json');
 
$client->putItem([
    'TableName' => 'events',
    'Item'      => $marshaler->marshalJson($json)
]);
?>