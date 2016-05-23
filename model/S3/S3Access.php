<?php


class S3Access {
    private static $BUCKET = 'cherry-shared-content';
    private $client;
    
    public function __construct($s3Client) {
        $this->client = $s3Client;
    }
    
    public function createFile($name, $path) {
        try {
            $result = $this->client->/*putObject*/putItem(array(
                'TableName' => S3Access::$BUCKET,
                    'Item' => array(
                        'name'        => $name,
                        'path' => $path
                        
                        )
                /*
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name,
                'SourceFile' => $path*/
            ));
            return $result['ObjectURL'];
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    public function getFile($name) {
        try {
            $result = $this->client->getObject(array(
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name
            ));
            return $result;
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    public function deleteFile($name) {
        try {
            $this->client->deleteObject(array(
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name
            ));
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
}
