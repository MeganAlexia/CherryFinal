<?php


class ChildDAO extends UserDAO {
    
    public function __construct ($client) {
        parent::__construct($client);
    }
    
    public function userToArray ($child) {
        $array = parent::userToArray($child);
        $arrayTeaching = $child->getTeachingContent();
        $arrayMedical = $child->getMedicalContent();
        $arrayFamily = $child->getfamilyContent();
        if (!empty($arrayTeaching)) {$array['teachingContent'] = array('L' => $arrayTeaching);}
        if (!empty($arrayMedical)) {$array['medicalContent'] = array('L' => $arrayMedical);}
        if (!empty($arrayFamily)) {$array['familyContent'] = array('L' => $arrayFamily);}
        $array['teacherId'] = array('S' => $child->getTeacherId());
        $array['familyId'] = array('S' => $child->getFamilyId());
        $array['doctorId'] = array('S' => $child->getDoctorId());
        return $array;
    }
    
    public function getChildren($emailAdult){
        try {
            $result = $this->client->scan([
                'TableName' => UserDAO::$TABLE_NAME,
                'ExpressionAttributeValues' => [
                    ':val1' => ['S' => $emailAdult]
                ],    
                'FilterExpression' => '(:val1=familyId) or (:val1=doctorId) or (:val1=teacherId) ',
            ]);
            $childrenDTO = $result['Items'];
            $children = array();
            foreach($childrenDTO as $childDTO) {
                $child = new Child();
                $this->fillUserAttributes($childDTO, $child);
                array_push($children, $child);
            }
            return $children;
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    protected function getUser () {
        return new Child();
    }
    
    protected function fillUserAttributes ($childDTO, $child) {
        parent::fillUserAttributes($childDTO, $child);
        $child->setTeachingContent($childDTO['teachingContent']['L']);
        $child->setMedicalContent($childDTO['medicalContent']['L']);
        $child->setFamilyContent($childDTO['familyContent']['L']);
        $child->setTeacherId($childDTO['teacherId']['S']);
        $child->setFamilyId($childDTO['familyId']['S']);
        $child->setDoctorId($childDTO['doctorId']['S']);
    }
}

