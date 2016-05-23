<?php
require "../../includes.php";

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$marshaler = new Marshaler();

function pluralize($string)
{
  preg_match("|^([0-9]+)(.*)|",$string,$matched);
  if($matched[1] > 1) {
      return number_format($matched[1]) . $matched[2] . 's';
  }
  return $string;
}

function calculateDiff($dayDelta, $minuteDelta)
{
  if($dayDelta != 'null' && $dayDelta != '0') {
      if($dayDelta < 0)
          $diff = "-".pluralize(abs(intval($dayDelta))." day");
      else if($dayDelta > 0)
          $diff = "+".pluralize(intval($dayDelta)." day");
  }
  else
      $diff = "";
        if($minuteDelta != 'null' && $minuteDelta != '0') {
      if($minuteDelta < 0)
          $diff .= "-".pluralize(abs(intval($minuteDelta))." minute");
      else
          $diff .= "+".pluralize(intval($minuteDelta)." minute");
  }
  return $diff;
}

$client = LocalDBClientBuilder::get(); //DynamoDbClientBuilder::get();

$array = array();

$owner = $_SESSION['email'];

$tableName = 'events';

$eav = $marshaler->marshalJson('
    {
        ":id": $_SESSION["id"];
    }
');

$params = [
    'TableName' => $tableName,
    'ExpressionAttributeValues'=> $eav
];

try {
    $result = $dynamodb->query($params);

    echo "Query succeeded.\n";

    foreach ($result['Items'] as $row) {
         $events[] = $row;
    }

    echo json_encode($events);
} catch (DynamoDbException $e) {
    echo json_encode(array('error' => 'Connection failed: ' . $e->getMessage()));
}

?>
