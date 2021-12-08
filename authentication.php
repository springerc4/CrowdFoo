<?php
require_once('settings.php');
require_once('sqlfunctions.php');

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
		$_SESSION['email'] = $email;
		header('Location: index.php');
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
		$_SESSION['email'] = $email;
		echo '<div class="alert alert-success" role="alert">Welcome Back!</div>';
	}
}

function signout() {
	$_SESSION['logged'] = "false";
	header('Location: index.php');
	echo '<div class="alert alert-success" role="alert">See Ya Next Time!</div>';
	session_destroy();
	
}

?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Sign Up</title>
    </head>
	<body>
<?php
	if ($_GET['auth'] == "register") {
?>
	<form method="post">
		<div class="mb-3">
			<label for="email" class="form-label">Email address</label>
			<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" name="password">
		</div>
		<div class="col-auto">
			<span id="passwordHelpInline" class="form-text">
				Must be 8-16 characters long.
			</span>
		</div>
		<br><br>
		<div class="mb-3">
			<label for="firstname" class="form-label">First Name</label>
			<input type="text" class="form-control" id="firstname" name="firstname">
		</div>
		<div class="mb-3">
			<label for="lastname" class="form-label">Last Name</label>
			<input type="text" class="form-control" id="lastname" name="lastname">
		</div>
	
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
			<a href="index.php" style="text-decoration: none; color: black;">Cancel</a>
		</button>
		<button type="submit" class="btn btn-primary">Submit</button>
		
	</form>
<?php
	} else if ($_GET['auth'] == "login") {
?>
	<form method="post">
		<div class="mb-3">
			<label for="email" class="form-label">Email address</label>
			<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" name="password">
		</div>
		<br><br>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
			<a href="index.php" style="text-decoration: none; color: black;">Cancel</a>
		</button>
		<button type="submit" class="btn btn-primary">Sign In</button>
	</form>
<?php
	} else if ($_GET['auth'] == 'logout'){ 
?>
	<div class="modal fade" id="signoutmodal" tabindex="-1" aria-labelledby="signoutmodallabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign Out?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <input name="some" value="value" />
                    <div class="modal-body">
                        Are you sure you want to sign out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary">
							<a href="index.php" style="text-decoration: none; color: black;">Cancel</a>
						</button>
                        <button type="submit" class="btn btn-primary">Sign Out</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
<?php
	}
?>
	</body>
</html>