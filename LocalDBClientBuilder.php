<?php

use Aws\DynamoDb\DynamoDbClient;
use Aws\Sdk;

use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Guzzle\Plugin\Log\LogPlugin;

class LocalDBClientBuilder {
    private static $client = null;
    
    private function __construct () {}
    
    public static function get () {
        
        if (LocalDBClientBuilder::$client == null) {
            LocalDBClientBuilder::$client = DynamoDbClient::factory(array(
                'region'   => 'us-west-2',
                'version'  => 'latest',
                'endpoint' => 'http://localhost:8000',
                'key' => 'myKey',
                'secret'  => 'mySecret'
            ));
           // AMO: TODO remove debug plugin 
            //LocalDBClientBuilder::$client->addSubscriber(LogPlugin::getDebugPlugin());
          
           
        }
        return LocalDBClientBuilder::$client;
                    
            
       /* $sdk = new Aws\Sdk([
            'region'   => 'us-west-2',
            'version'  => 'latest',
            'endpoint' => 'http://localhost:8080'
        ]);

        $dynamodb = $sdk->createDynamoDb();*/
    }
}

/*class DynamoDbClientBuilder {
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
}*/
