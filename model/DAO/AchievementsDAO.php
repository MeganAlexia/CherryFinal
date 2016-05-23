<?php


class AchievementsDAO {
    public static $TABLE_NAME = 'Achievements';
    protected $client;
        
    public function __construct ($client) {
        $this->client = $client;
    }
    
    public function getNinja($email){
        $dto = $this->getAchievementDTO($email);        
        return $dto['isNinja']['N'];
    }
    
    public function getCurious($email){
        $dto = $this->getAchievementDTO($email);        
        return $dto['isCurious']['N'];
    }
    
    
    public function getTutorial($email){
        $dto = $this->getAchievementDTO($email);
        //print_r($dto);
        return $dto['isTutoFinished']['N'];
    }
    public function getAvatar($email){
        $dto = $this->getAchievementDTO($email);
        //print_r($dto);
        return $dto['isAvatarMale']['N'];
    }
    
    private function update($email,$value,$field){
         $result = $this->client->updateItem([
    'TableName' => 'Achievements',
    'Key' => array(
                'email' => array('S' => $email)
            ),
    'ExpressionAttributeValues' =>  [
        ':val1' => [ 'N' => $value]  
       
    ] ,
    'UpdateExpression' => 'set '.$field.' = :val1 '
]);
        print_r($result);
        
    }
    
     public function updateNinja($email,$value){
        $this->update($email, $value, 'isNinja');
     }
     
    public function updateCurious($email,$value){
        $this->update($email, $value, 'isCurious');
     }
    
     public function updateAvatar($email,$value){
        $this->update($email, $value, 'isAvatarMale');
     }
    
    public function updateTutorial($email,$value){
        $this->update($email, $value, 'isTutoFinished');
        //echo 'value' .$value;
      /*  $result = $this->client->updateItem([
    'TableName' => 'Achievements',
    'Key' => array(
                'email' => array('S' => $email)
            ),
    'ExpressionAttributeValues' =>  [
        ':val1' => [ 'N' => $value]  
       
    ] ,
    'UpdateExpression' => 'set isTutoFinished = :val1 '
]);
        print_r($result);*/
    }
    
    public function getAchievementDTO($email){
        $result = $this->client->getItem(array(
            'ConsistentRead' => true,
            'TableName' => AchievementsDAO::$TABLE_NAME,
            'Key' => array(
                'email' => array('S' => $email)
            )
        ));
        //print_r($result['Item']);
        return $result['Item'];
    }
   
}
