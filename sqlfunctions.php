<?php
require_once('settings.php');

function contains($db, $email) {
    $email = array($email);
    $stmt = $db->prepare('SELECT email FROM users');
    $stmt->execute();
    //while($user = $stmt->fetch())
    
}
    $email = 'camspringer7@outlook.com';
    $stmt = $db->prepare('SELECT email FROM users');
    $stmt->execute();
    while($row = $user = $stmt->fetch()){
        print_r($row);
        echo "\n";
        if ($row['email'] == $email){
            echo 'true';
            return true;
        }
        else{
            echo 'false';
            return false;
        }
    }

    