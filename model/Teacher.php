<?php

class Teacher extends User {
    
    public function __construct() {
        $this->type = "teacher";
    }
}
