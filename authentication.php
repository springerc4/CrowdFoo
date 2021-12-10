<?php
require_once('settings.php');
require_once('sqlfunctions.php');

session_start();

$auth_sql = new UserAuth($db);

class UserAuth {

	private $db = null;

	private $sql_ops;

	function __construct($db) {
		$this->db = $db;
		$this->sql_ops = new SqlOperation($db);
	}


	public function signup($email, $password, $first_name, $last_name) {
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
		}
		else if(strlen($password) < 8 || strlen($password) > 16) {
			echo '<div class="alert alert-warning" role="alert">Password Must Be Between 8 and 16 Characters</div>';
		}
		else if (!strlen($first_name) > 0 || !strlen($last_name) > 0) {
			echo '<div class="alert alert-warning" role="alert">Please Enter Your First and Last Name</div>';
		}
		else if ($this->sql_ops->contains($email)) {
			echo '<div class="alert alert-warning" role="alert">Email Already Registered.</div>';
		}
		else {
			$query = $this->db->prepare('INSERT INTO users (email, user_password, first_name, last_name) VALUES (:email, :pass, :fname, :lname) ');
			$query->bindParam(':email', $email);
			$query->bindParam(':pass', $password);
			$query->bindParam(':fname', $first_name);
			$query->bindParam(':lname', $last_name);
			$query->execute();
			$_SESSION['logged'] = "true";
			$_SESSION['email'] = $email;
			$idQuery = $this->db->prepare('SELECT user_password, first_name, last_name, user_ID FROM users WHERE email = ?');
			$idQuery->execute([$_SESSION['email']]);
			$idRow = $idQuery->fetch();
			$_SESSION['userID'] = $idRow['user_ID'];
			$_SESSION['password'] = $idRow['user_password'];
			$_SESSION['firstname'] = $idRow['first_name'];
			$_SESSION['lastname'] = $idRow['last_name'];
			$_SESSION['admin'] = 0;
			echo '<div class="alert alert-success" role="alert">You are Officially Registered! <a href="index.php">Return to Index</a></div>';
		}
		
	}

	public function signin($email, $password) {
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
		}
		else if (!$this->sql_ops->contains($email, $password) || !strlen($password) > 0) {
			echo '<div class="alert alert-warning" role="alert">Account Not Found</div>';
		}
		else {
			$_SESSION['logged'] = 'true';
			$_SESSION['email'] = $email;
			$idQuery = $this->db->prepare('SELECT user_ID, user_password, first_name, last_name, isAdmin FROM users WHERE email = ?');
			$idQuery->execute([$_SESSION['email']]);
			$idRow = $idQuery->fetch();
			$_SESSION['userID'] = $idRow['user_ID'];
			$_SESSION['password'] = $idRow['user_password'];
			$_SESSION['firstname'] = $idRow['first_name'];
			$_SESSION['lastname'] = $idRow['last_name'];
			$_SESSION['admin'] = $idRow['isAdmin'];
			echo '<div class="alert alert-success" role="alert">Welcome Back! <a href="index.php">Return to Index</a></div>';
		}
	}

	public function signout() {
		$_SESSION['logged'] = "false";
		session_destroy();
	}
}


	if (count($_POST) > 0) {
		if ($_GET['auth'] == "login") {
			if (!isset($_POST['email'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter an Email Address.</div>';
				die();
			}
			else if (!isset($_POST['password'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter a Password</div>';
				die();
			}
			else {
				$auth_sql->signin($_POST['email'], $_POST['password']);
			}
		}
		else if ($_GET['auth'] == "register") {
			if (!isset($_POST['email'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter an Email Address.</div>';
			}
			else if (!isset($_POST['password'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter a Password</div>';
			}
			else if (!isset($_POST['firstname'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter your First Name</div>';
			}
			else if (!isset($_POST['lastname'])) {
				echo '<div class="alert alert-warning" role="alert">Please Enter your Last Name</div>';
			}
			else {
				$auth_sql->signup($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname']);
			}
		}
		else {
			if (isset($_POST['signout'])) {
				$auth_sql->signout();
				echo '<div class="alert alert-success" role="alert">See Ya Next Time! <a href="index.php">Return to Index</a></div>';
			}
		}
	}

?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Authentication</title>
    </head>
	<body>
<?php
	if ($_GET['auth'] == "register") {
?>
	<h3 style="text-align: center; margin-top: 7%;">Sign Up</h3>
	<div class="card" style="width: 40%; margin-left: 30%; margin-top: 3%;">
		<div class="card-body">
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
					<a href="index.php" style="text-decoration: none; color: white;">Cancel</a>
				</button>
				<button type="submit" class="btn btn-primary">Sign Up</button>
			
			</form>
		</div>
	</div>
<?php
	} else if ($_GET['auth'] == "login") {
?>
	<h3 style="text-align: center; margin-top: 7%;">Sign In</h3>
	<div class="card" style="width: 18rem; margin-left: 40%; margin-top: 5%; ">
		<div class="card-body">
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
				<button type="button" class="btn btn-secondary">
					<a href="index.php" style="text-decoration: none; color: white;">Cancel</a>
				</button>
				<button type="submit" class="btn btn-primary">Sign In</button>
			</form>
		</div>
	</div>
<?php
	} else { 
?>
		<div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Sign Out?</h5>
					<p class="card-text">Are you sure you want to sign out?</p>
					<button type="button" class="btn btn-secondary">
						<a href="index.php" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="signout">Submit</button>
				</div>
			</form>
		</div>
<?php
	}
?>
	</body>
</html>