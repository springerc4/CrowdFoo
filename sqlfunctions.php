<?php
require_once('settings.php');
require_once('authentication.php');

function contains($db, $email, $password = null) {
    $query = $db->prepare('SELECT * FROM users WHERE email = ?');
    $query->execute([$email]);
    $row = $query->fetch();
    if ($row) {
        if ($password == null) {
            return true;
        }
        else {
            return $row['user_password'] == $password;
        }
    }
    else {
        return false;
    }
}
