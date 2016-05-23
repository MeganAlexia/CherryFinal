<?php
$root = "./";
include 'includes.php'; 

$Adao = new AchievementsDAO(LocalDBClientBuilder::get());//DynamoDbClientBuilder::get());

if(!empty($_GET['email'])){
    $email = $_GET['email'];
    if(empty($_GET['tuto'])){
        $tuto = $Adao->getTutorial($email);

        header('Content-Type: application/json');

        $response = json_encode(array($tuto));
        echo $response;
    }
    else{
        $tuto = $_GET['tuto'];
        echo $tuto;
        $Adao->updateTutorial($email, $tuto);
        echo 'a';
    }
}
?>

