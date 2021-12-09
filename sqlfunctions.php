<?php
require_once('settings.php');

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

function deleteAccount($db, $user_id) {
    $query = $db->prepare('DELETE FROM users WHERE user_ID = ?');
    $query->execute([$user_id]);
    $_SESSION['logged'] = 'false';
}

function modifyAccount($db, $email, $password, $fname, $lname, $admin) {
   
}

function setDefaultSession() {
    $_SESSION['email'] = null;
    $_SESSION['admin'] = 0;
    $_SESSION['password'] = null;
    $_SESSION['firstname'] = null;
    $_SESSION['lastname'] = null;
    $_SESSION['userID'] = 0;
}
