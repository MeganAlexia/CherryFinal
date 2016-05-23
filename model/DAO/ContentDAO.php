<?php


class ContentDAO {
    private static $TABLE_NAME = 'Contents';
    private $client;
    
    public function __construct ($dynamoClient) {
        $this->client = $dynamoClient;
    }
    
    // children = array of [email, date]
    public function create ($content, $children) {
        try {
            $this->client->putItem(array(
                'TableName' => ContentDAO::$TABLE_NAME,
                'Item' => array(
                    'name'    => array('S' => $content->getName()),
                    'owner'   => array('S' => $content->getEmailOwner()),
                    'url'     => array('S' => $content->getUrl()),
                    'type'    => array('S' => $content->getType())
                    )
            ));                       
            $length = count($children);
            $childDAO = new ChildDAO($this->client);
            for ($i = 0; $i < $length; $i++) {
                $email = $children[$i]['email'];
                $child = $childDAO->get($email);
                if ($child != null) {
                    $dateStart = $children[$i]['dateStart'];
                    $dateEnd = $children[$i]['dateEnd']; 
                    $child->addContent(
                            $content,
                            $dateStart,
                            $dateEnd);
                    $childDAO->update($child);
                }
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }
    
    //méthode à tester
    public function get($name, $owner) {
        $result = $this->client->getItem(array(
            'ConsistentRead' => true,
            'TableName' => ContentDAO::$TABLE_NAME,
            'Key' => array(
                'name' => array('S' => $name),
                'owner' => array('S' => $owner)
            )
        ));
        $content = $this->toModel($result['Item']);
        return $content;
    }
    
    // GET all the contents posted by an adult
    public function getContentsOfUser($email) {
        try {
            $result = $this->client->scan([
                'TableName' => ContentDAO::$TABLE_NAME,
                'ExpressionAttributeNames' => [
                    // 'owner' is a keyword for DynamoDB
                    // we need to define an expression attribute name
                    '#owner' => 'owner'
                ], 
                'ExpressionAttributeValues' => [
                    ':val1' => ['S' => $email]
                ],    
                'FilterExpression' => '(:val1=#owner)',
            ]);
            $contentsDTO = $result['Items'];
            if (empty($contentsDTO)) {return null;}
            $array = array();
            foreach ($contentsDTO as $dto) {
                array_push($array, $this->toModel($dto));
            }
            return $array;
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    public function delete ($name, $owner) {
        try {
            // DELETE the file from DynamoDB, table 'Contents'
            $this->client->deleteItem(array(
                'TableName' => ContentDAO::$TABLE_NAME,
                'Key' => array(
                    'name'    => array('S' => $name),
                    'owner'   => array('S' => $owner)
                    )
            ));
            
            // GET the owner's type of the file
            $userDao = new UserDAO($this->client);
            $user = $userDao->get($owner);
            $type = $user->getType();
            
            // GET the children of the owner
            $childDao = new ChildDAO($this->client);
            $children = $childDao->getChildren($owner);
            
            // DELETE the file from children arrays
            $length = count($children);
            for ($i = 0; $i < $length; $i++) {
                $child = $children[$i];
                $child->deleteContent($name, $owner, $type);
                $childDao->update($child);
            }
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    private function toModel($dto) {
        // Convert DTO into model object
        $content = new Content();
        $content->setName($dto['name']['S']);
        $content->setType($dto['type']['S']);
        $content->setEmailOwner($dto['owner']['S']);
        $content->setUrl($dto['url']['S']);
        return $content;
    }
}
