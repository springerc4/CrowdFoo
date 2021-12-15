<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    $modify_sql = new SqlOperation($db);


    if (isset($_POST['modifyaddress'])) {
        $address_info = $modify_sql->addressInfo($_SESSION['userID']);
        if (!strlen($_POST['city']) > 0) {
            $_POST['city'] = $address_info['city'];
        }

        if (!strlen($_POST['state']) > 0) {
            $_POST['state'] = $address_info['_state'];
        }

        if (!strlen($_POST['country']) > 0) {
            $_POST['country'] = $address_info['country'];
        }

        if (!strlen($_POST['zipcode']) > 0) {
            $_POST['zipcode'] = $address_info['zipcode'];
        }

        $modify_sql->modifyAddress($_POST['city'], $_POST['state'], $_POST['country'], $_POST['zipcode'], $_SESSION['userID']);
        echo '<div class="alert alert-success" role="alert">Your Address has been Modified. <a href="account.php">Return to Account</a></div>';
    } 
    if (isset($_POST['modifyaccount'])) {
        if (!strlen($_POST['email']) > 0) {
            $_POST['email'] = $_SESSION['email'];
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
        }

        if (!strlen($_POST['password']) > 0) {
            $_POST['password'] = $_SESSION['password'];
        }

        if (!strlen($_POST['firstname']) > 0) {
            $_POST['firstname'] = $_SESSION['firstname'];
        }

        if (!strlen($_POST['lastname']) > 0) {
            $_POST['lastname'] = $_SESSION['lastname'];
        }

        if ($modify_sql->contains($_POST['email'])) {
            echo '<div class="alert alert-warning" role="alert">Email Already Registered.</div>';
            $_POST['email'] = $_SESSION['email'];
        } else {
            $modify_sql->modifyAccount($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], isset($_POST['admin']));
            echo '<div class="alert alert-success" role="alert">Your Account has been Modified. <a href="index.php">Return to Index</a></div>';
        }
    }

    if (isset($_POST['modifyreward'])) {
        $reward_id = $_GET['rewardid'];
        $reward_row = $modify_sql->rewardInfo($_GET['rewardid']);
        $reward_project_id = $reward_row['project_ID'];
        
        if (!strlen($_POST['name']) > 0) {
            $_POST['name'] = $reward_row['reward_name'];
        }

        if (!strlen($_POST['price']) > 0) {
            $_POST['price'] = $reward_row['reward_price'];
        }

        if (!strlen($_POST['description']) > 0) {
            $_POST['description'] = $_SESSION['reward_description'];
        }

        $modify_sql->modifyReward($_POST['name'], $_POST['price'], $_POST['description'], $reward_id);
        echo '<div class="alert alert-success" role="alert">Reward has been Modified. <a href="index.php">Return to Index</a></div>';
    }

    if (isset($_POST['modifyproject'])) {
        $project_id = $_GET['projectid'];
        $project_info = $modify_sql->projectInfo($project_id);
        if (!strlen($_POST['projectname']) > 0) {
            $_POST['projectname'] = $project_info['project_name'];
        }

        if (!strlen($_POST['projectdescription']) > 0) {
            $_POST['projectdescription'] = $project_info['project_description'];
        }

        if (!strlen($_POST['goal']) > 0) {
            $_POST['goal'] = $project_info['project_goal'];
        }

        $modify_sql->modifyProject($_POST['projectname'], $_POST['projectdescription'], $_POST['goal'], $project_id);
        echo '<div class="alert alert-success" role="alert">Project has been Modified. <a href="project.php?projectid='.$project_id.'">Return to Index</a></div>';
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Modify</title>
    </head>
    <?php
        if ($_GET['entity'] == "account") {
    ?>
    <body>
        <h3 style="text-align: center; margin-top: 7%;">Modify Account</h3>
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
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="admincheck" name="admin">
                        <label class="form-check-label" for="admincheck">Admin?</label>
                    </div>
                    <br><br>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <a href="account.php" style="text-decoration: none; color: white;">Cancel</a>
                    </button>
                    <button type="submit" class="btn btn-primary" name="modifyaccount">Modify</button>
                
                </form>
            </div>
        </div>

<?php
    } else if ($_GET['entity'] == "address") {
?>
        <h3 style="text-align: center; margin-top: 7%;">Modify Address</h3>
        <div class="card" style="width: 40%; margin-left: 30%; margin-top: 3%;">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state">
                    </div>
                    <br><br>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country">
                    </div>
                    <div class="mb-3">
                        <label for="zipcode" class="form-label">Zipcode</label>
                        <input type="text" class="form-control" id="zipcode" name="zipcode">
                    </div>
                    <br><br>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <a href="account.php" style="text-decoration: none; color: white;">Cancel</a>
                    </button>
                    <button type="submit" class="btn btn-primary" name="modifyaddress">Modify</button>
                
                </form>
            </div>
        </div>



<?php
    } else if ($_GET['entity'] == "reward") {
?>
        <h3 style="text-align: center; margin-top: 7%;">Modify Reward</h3>
        <div class="card" style="width: 40%; margin-left: 30%; margin-top: 3%;">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Reward Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Reward Price</label>
                        <input type="text" class="form-control" id="price" name="price">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Reward Description</label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>
                    <br><br>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <a href="project.php?projectid=<?php $reward_project_id ?>" style="text-decoration: none; color: white;">Cancel</a>
                    </button>
                    <button type="submit" class="btn btn-primary" name="modifyreward">Modify</button>
                
                </form>
            </div>
        </div>

<?php
    } else if ($_GET['entity'] == "project") {
?>
        <h3 style="text-align: center; margin-top: 7%;">Modify Project</h3>
        <div class="card" style="width: 40%; margin-left: 30%; margin-top: 3%;">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="projectname" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectname" name="projectname">
                    </div>
                    <div class="mb-3">
                        <label for="projectdescription" class="form-label">Project Description</label>
                        <input type="text" class="form-control" id="projectdescription" name="projectdescription">
                    </div>
                    <div class="mb-3">
                        <label for="goal" class="form-label">Project Goal</label>
                        <input type="text" class="form-control" id="goal" name="goal">
                    </div>
                    <br><br>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <a href="project.php?projectid=<?php $project_id ?>" style="text-decoration: none; color: white;">Cancel</a>
                    </button>
                    <button type="submit" class="btn btn-primary" name="modifyproject">Modify</button>
                
                </form>
            </div>
        </div>
<?php
    } else {
        echo 'Page Not Found';
    }
?>
