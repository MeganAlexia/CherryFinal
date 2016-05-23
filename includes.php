<?php
    $_model = $root.'model/';
    $_dao = $_model.'DAO/';
    $_s3 = $_model.'S3/';
    $_calendar = $root.'calendar/';
    
    require_once ($root.'vendor/autoload.php');
    
    require_once ($root.'S3ClientBuilder.php');
    //require_once ($root.'DynamoDbClientBuilder.php');
    require_once ($root.'LocalDBClientBuilder.php');
    
    require_once($root.'Rights.php');
    
   // require_once ($_calendar.'Date.php');
   // require_once ($_calendar.'DaysOfYear.php');
    
    require_once ($_s3.'S3Access.php');
        
    require_once ($_model.'Content.php');
    require_once ($_model.'User.php');
    require_once ($_model.'Child.php');
    require_once ($_model.'Doctor.php');
    require_once ($_model.'Family.php');
    
    require_once ($_dao.'ContentDAO.php');
    require_once ($_dao.'UserDAO.php');
    require_once ($_dao.'ChildDAO.php');
    require_once ($_dao.'DoctorDAO.php');
    require_once ($_dao.'FamilyDAO.php');
    require_once ($_dao.'AchievementsDAO.php');
/*
    
    if (!class_exists('DynamoDbClientBuilder')){require_once('../DynamoDbClientBuilder.php');}
    if (!class_exists('S3ClientBuilder')){require_once('../S3ClientBuilder.php');}
    
    if (!class_exists('ChildDAO')){require_once('../model/DAO/ChildDAO.php');}
    if (!class_exists('ContentDAO')){require_once('../model/DAO/ContentDAO.php');}
    if (!class_exists('DoctorDAO')){require_once('../model/DAO/DoctorDAO.php');}
    if (!class_exists('FamilyDAO')){require_once('../model/DAO/FamilyDAO.php');}
    if (!class_exists('UserDAO')){require_once('../model/DAO/UserDAO.php');}
    
    if (!class_exists('Child')){require_once('../model/Child.php');}
    if (!class_exists('Content')){require_once('../model/Content.php');}
    if (!class_exists('Doctor')){require_once('../model/Doctor.php');}
    if (!class_exists('Family')){require_once('../model/Family.php');}
    if (!class_exists('User')){require_once('../model/User.php');}
    
    if (!class_exists('S3Access')){require_once('../model/S3/S3Access.php');}
    */
