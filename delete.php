<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    $delete_sql = new SqlOperation($db);


    if (isset($_POST['deleteaccount'])) {
        $delete_sql->deleteAccount($_SESSION['userID']);
        echo '<div class="alert alert-success" role="alert">Your Account has been Deleted. <a href="index.php">Return to Index</a></div>';
    }

    if (isset($_POST['deleteaddress'])) {
        $delete_sql->deleteAddress($_SESSION['userID']);
        echo '<div class="alert alert-success" role="alert">Your Address has been Deleted. <a href="account.php">Return to Account</a></div>';
    }

    if (isset($_POST['deletereward'])) {
        $reward_id = $_GET['rewardid'];
        $reward_row = $delete_sql->rewardInfo($reward_id);
        $reward_project_id = $reward_row['project_ID'];
        $delete_sql->deleteReward($reward_id);
        echo '<div class="alert alert-success" role="alert">Reward has been Deleted. <a href="project.php?projectid='.$_GET['projectid'].'">Return to Project</a></div>';
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Delete</title>
    </head>
    <?php
        if ($_GET['entity'] == "account") {
    ?>
    <body>
        <div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Deleting Your Account?</h5>
					<p class="card-text">Are you sure you want to delete your account?</p>
					<button type="button" class="btn btn-secondary">
						<a href="account.php" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="deleteaccount">Delete</button>
				</div>
			</form>
		</div>
    <?php
        } else if ($_GET['entity'] == "address") {
    ?>
        <div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Deleting Your Shipping Address?</h5>
					<p class="card-text">Are you sure you want to delete this address?</p>
					<button type="button" class="btn btn-secondary">
						<a href="account.php" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="deleteaddress">Delete</button>
				</div>
			</form>
		</div>
    <?php
        } else if ($_GET['entity'] == "reward") {
            $reward_id = $_GET['rewardid'];
            $reward_row = $delete_sql->rewardInfo($reward_id);
            //$reward_project_id = $reward_row['project_ID'];
    ?>
        <div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Deleting Reward</h5>
					<p class="card-text">Are you sure you want to delete this reward?</p>
					<button type="button" class="btn btn-secondary">
						<a href="project.php?projectid=<?=$_GET['projectid']?>" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="deletereward">Delete</button>
				</div>
			</form>
		</div>
    <?php
        } else if ($_GET['entity'] == "project") {
            $project_id = $_GET['projectid'];
            $project_info = $delete_sql->getProject($project_id);
    ?>
        <div class="card" style="width: 30%;">
			<form method="post">
				<div class="card-body">
					<h5 class="card-title">Deleting Your Project?</h5>
					<p class="card-text">Are you sure you want to delete <?=$project_info['project_name'] ?>?</p>
					<button type="button" class="btn btn-secondary">
						<a href="project.php?projectid=<?php $project_id ?>" style="text-decoration: none; color: white;">Cancel</a>
					</button>
					<button type="submit" class="btn btn-primary" name="deleteproject">Delete</button>
				</div>
			</form>
		</div>

    <?php
        } else {
            echo 'Page not found.';
        }

        if (isset($_POST['deleteproject'])) {
            $delete_sql->deleteProject($project_id);
            header('location: index.php');
            echo '<div class="alert alert-success" role="alert">Project has been Deleted. <a href="index.php">Return to Index</a></div>';
        }
    ?>