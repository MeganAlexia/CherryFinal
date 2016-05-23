<?php

class Child extends User {
    private $familyId;
    private $doctorId;
    private $teacherId;
    private $familyContent;
    private $medicalContent;
    private $teachingContent;
    private $dateOfArrival;
    private $dateOfDeparture;
    private $treatments;


    public function __construct() {
        $this->type = "child";
    }
    
    function getFamilyId() {
        return $this->familyId;
    }

    function getDoctorId() {
        return $this->doctorId;
    }

    function getTeacherId() {
        return $this->teacherId;
    }

    function setFamilyId($familyId) {
        $this->familyId = $familyId;
    }

    function setDoctorId($doctorId) {
        $this->doctorId = $doctorId;
    }

    function setTeacherId($teacherId) {
        $this->teacherId = $teacherId;
    }
    
    function getFamilyContent() {
        return $this->familyContent;
    }

    function getMedicalContent() {
        return $this->medicalContent;
    }

    function getTeachingContent() {
        return $this->teachingContent;
    }

    function setFamilyContent($familyContent) {
        $this->familyContent = $familyContent;
    }

    function setMedicalContent($medicalContent) {
        $this->medicalContent = $medicalContent;
    }

    function setTeachingContent($teachingContent) {
        $this->teachingContent = $teachingContent;
    }
    
    public function isFather($adultId) {
        return  ($this->doctorId == $adultId) ||
                ($this->familyId == $adultId) ||
                ($this->teacherId == $adultId);
    }
    
    public function addContent($content, $dateStart, $dateEnd) {
        print 'date!Start == '.$dateStart;
        print 'date!End == '.$dateEnd;
        $elt = array ('M' => array (
                        'name' => array ('S' => $content->getName()),
                        'owner' => array ('S' => $content->getEmailOwner()),
                        'dateStart' => array ('S' => $dateStart),
                        'dateEnd' => array ('S' => $dateEnd),
                        'notified' => array ('N' => 0)
                    ));
        $type = $content->getType();
        if ($type == "doctor") {
            $this->addContentIfMissing($this->medicalContent, $elt);
        } else if ($type == "teacher") {
            $this->addContentIfMissing($this->teachingContent, $elt);
        } else if ($type == "family") {
            $this->addContentIfMissing($this->familyContent, $elt);
        }
    }
    
    public function deleteContent($name, $owner, $type) {
        $contents = $this->getContentByType($type);
        $i = 0;
        foreach($contents as $content) {
            if ($content['M']['name']['S'] == $name &&
                $content['M']['owner']['S'] == $owner) {
                // delete content from array
                unset($contents[$i]);
                break;
            }
            $i++;
        }
        // index array correctly after use of unset function
        $contents = array_values($contents);
        $this->setContentByType($contents, $type);        
    }
    
    private function addContentIfMissing (&$array, $elt) {
        $eltIsInArray = false;
        $length = count($array);
        if ($length == 0) {
            $array = array($elt);
            return;
        }
        for ($i = 0; $i < $length; $i++) {
            $e = $array[$i];
            if ($e['M']['name']['S'] == $elt['M']['name']['S'] &&
                $e['M']['owner']['S'] == $elt['M']['owner']['S']) {
                
                if ($e['M']['dateStart']['S'] != $elt['M']['dateStart']['S'] ||
                    $e['M']['dateEnd']['S'] != $elt['M']['dateEnd']['S']) {
                    // l'element est deja dans le tableau il faut juste changer la date
                    $array[$i]['M']['dateStart']['S'] = $elt['M']['dateStart']['S'];
                    $array[$i]['M']['dateEnd']['S'] = $elt['M']['dateEnd']['S'];
                    $array[$i]['M']['notified']['N'] = 0;
                }
                $eltIsInArray = true;
                break;
            }
        }
        if (!$eltIsInArray) {
            array_push($array, $elt);
        }
    }
    
    function readContent($name, $type) {
        $array = &$this->getContentByType($type);
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            $e = $array[$i];
            if ($e['M']['name']['S'] == $name) {
                $array[$i]['M']['notified']['N'] = 1;
                break;
            }
        }
    }
    
    function isNotified($name, $type) {
        $array = $this->getContentByType($type);
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            $e = $array[$i];
            if ($e['M']['name']['S'] == $name &&
                $e['M']['notified']['N'] == 1) {
                return true;
            }
        }
        return false;
    }
    
    public function &getContentByType($type) {
        if ($type == "doctor") {
            return $this->medicalContent;
        } else if ($type == "teacher") {
            return $this->teachingContent;
        } else if ($type == "family") {
            return $this->familyContent;
        }
    }
    
    function setContentByType($list_contents, $type) {
        if ($type == "doctor") {
            $this->medicalContent = $list_contents;
        } else if ($type == "teacher") {
            $this->teachingContent = $list_contents;
        } else if ($type == "family") {
            $this->familyContent = $list_contents;
        }
    }
    
    private function findContentsByStartingDate($date, $array) {
        $result = array();
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            if ($array[$i]['M']['dateStart']['S'] == $date) {
                array_push($result, $array[$i]);
            }
        }
        return $result;
    }
    
    public function getContentsByStartingDate($date, $type) {
        $content = $this->getContentByType($type);
        return $this->findContentsByStartingDate($date, $content);
    }
}
