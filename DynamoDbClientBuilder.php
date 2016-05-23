<?php

use Aws\DynamoDb\DynamoDbClient;

class DynamoDbClientBuilder {
    private static $client = null;
    
    private function __construct () {}
    
    public static function get () {
        if (DynamoDbClientBuilder::$client == null) {
            DynamoDbClientBuilder::$client = DynamoDbClient::factory(array(
                'region' => 'eu-west-1'
            ));
        }
        return DynamoDbClientBuilder::$client;
    }
}
