<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    if (isset($_POST['deleteaccount'])) {
        deleteAccount($db, $_SESSION['userID']);
        echo '<div class="alert alert-success" role="alert">Your Account has been Deleted. <a href="index.php">Return to Index</a></div>';
    }

    if ($_GET['entity'] == "account") {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Delete</title>
    </head>
    <body>
        <div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Deleting Your Account?</h5>
					<p class="card-text">Are you sure you want to delete your account?</p>
					<button type="button" class="btn btn-secondary">
						<a href="index.php" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="deleteaccount">Delete</button>
				</div>
			</form>
		</div>
    <?php
        } 
    ?>