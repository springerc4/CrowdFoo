<?php
require_once('settings.php');
require_once('sqlfunctions.php');
require_once('signup.php');

function signup($db, $email, $password, $first_name, $last_name) {
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
	}
	else if(strlen($password) < 8 || strlen($password) > 16) {
		echo '<div class="alert alert-warning" role="alert">Password Must Be Between 8 and 16 Characters</div>';
	}
	else if (contains($db, $email)) {
		echo '<div class="alert alert-warning" role="alert">Email Already Registered.</div>';
	}
	else {
		$query = $db->prepare('INSERT INTO users (email, user_password, first_name, last_name) VALUES (:email, :pass, :fname, :lname) ');
		$query->bindParam(':email', $email);
		$query->bindParam(':pass', $password);
		$query->bindParam(':fname', $first_name);
		$query->bindParam(':lname', $last_name);
		$query->execute();
		$_SESSION['logged'] = "true";
		echo '<div class="alert alert-success" role="alert">You are Officially Registered!</div>';
	}
	
}

function signin($db, $email, $password) {
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
	}
	else if (!contains($db, $email, $password)) {
		echo '<div class="alert alert-warning" role="alert">Account Not Found</div>';
	}
	else {
		$_SESSION['logged'] = 'true';
		echo '<div class="alert alert-success" role="alert">Welcome Back!</div>';
	}
}

function signout() {
	$_SESSION['logged'] = "false";
	echo '<div class="alert alert-success" role="alert">See Ya Next Time!</div>';
	session_destroy();

}
