<?php

// $type is in { "doctor", "teacher", "child", "family" }

class User {
    protected $type;
    protected $email;
    protected $password;
    protected $lastname;
    protected $firstname;
    
    public function getType() {
        return $this->type;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getLastname() {
        return $this->lastname;
    }
    
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }
    
    public function getFirstname() {
        return $this->firstname;
    }
    
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }
}