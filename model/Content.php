<?php

class Content {
    private $name;
    private $emailOwner;
    private $url;
    private $type; // $type is in {'teacher', 'doctor', 'family'}
    
    public function __construct() {}

    public function getName() {
        return $this->name;
    }

    function getUrl() {
        return $this->url;
    }

    function getEmailOwner() {
        return $this->emailOwner;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setEmailOwner($emailOwner) {
        $this->emailOwner = $emailOwner;
    }

    public function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }
}
