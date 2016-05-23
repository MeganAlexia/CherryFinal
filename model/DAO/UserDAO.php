<?php


class UserDAO {
    public static $TABLE_NAME = 'Users';
    protected $client;
        
    public function __construct ($client) {
        $this->client = $client;
    }
    
    public function get ($email) {
        
        $user = $this->getUser();
        $userDTO = $this->getUserDTO($email);
        if ($userDTO != null) {
            $this->fillUserAttributes($userDTO, $user);
            return $user;
        } else {
            return null;
        }
    }
    
    public function create ($user) {
        $arrayOfUser = $this->userToArray($user);
        print_r($arrayOfUser);
        try {
            $this->client->putItem(array(
                'TableName' => UserDAO::$TABLE_NAME,
                'Item' => $arrayOfUser
            ));
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    public function update ($user) {
        $this->create($user);
    }
    
    public function delete ($email) {
        $this->client->deleteItem(array(
            'TableName' => UserDAO::$TABLE_NAME,
            'Key' => array(
                'email' => array('S' => $email)
            )
        ));
    }
        
    protected function getUserDTO ($email) {
        
        $result = $this->client->getItem(array(
            'ConsistentRead' => true,
            'TableName' => UserDAO::$TABLE_NAME,
            'Key' => array(
                'email' => array('S' => $email)
            )
        ));
        
        return $result['Item'];
    }
    
    protected function getUser () {
        return new User();
    }
    
    protected function fillUserAttributes ($userDTO, $user) {
        $user->setEmail($userDTO['email']['S']);
        $user->setPassword($userDTO['password']['S']);
        $user->setLastname($userDTO['lastname']['S']);
        $user->setFirstname($userDTO['firstname']['S']);
        $user->setType($userDTO['type']['S']);
    }
    
    public function userToArray ($user) {
        $array = array(
            'email'     => array('S' => $user->getEmail()),
            'password'  => array('S' => $user->getPassword()),
            'lastname'  => array('S' => $user->getLastname()),
            'firstname' => array('S' => $user->getFirstname()),
            'type'      => array('S' => $user->getType())
        );
        return $array;
    } 
}
