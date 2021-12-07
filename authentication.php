<?php
require_once('settings.php');
require_once('sqlfunctions.php');

function signup($email, $password, $first_name, $last_name) {
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return 'Error: Invalid Email or Password';
	}
	else if(strlen($password) < 8 || strlen($password) > 16) {
		return 'Invalid: Password must be between 8 and 16 characters';
	}
	else if (contains($db, $email)) {
		echo 'Email already registered';
		return '<a href="signin.php">Sign in?</a>';
	}
	else {
		$query = $db->prepare('INSERT INTO users (email, user_password, first_name, last_name) VALUES (:email, :pass, :fname, :lname) ');
		$query->bindParam(':email', $email);
		$query->bindParam(':pass', $password);
		$query->bindParam(':fname', $first_name);
		$query->bindParam(':lname', $last_name);
		$query->execute();
		$_SESSION['logged'] = "true";
	}
	
}

function signin($email, $password) {
	
	if (!isset($_POST['email']) || !isset($_POST['password'])) {
		echo 'Email or Password is Invalid';
		return false;
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo 'Email or Password is Invalid';
		return false;
	}
	else if (!contains($db, $email, $password)) {
		return false;
	}
	else {
		$_SESSION['logged'] = 'true';
		return true;
	}
}

function signout() {
	$_SESSION['logged'] = "false";
	session_destroy();
	header('Location: index.php');
}
