<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    if (isset($_POST['modifyaccount'])) {
        modifyAccount($db, $_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], isset($_POST['admin']));
        echo '<div class="alert alert-success" role="alert">Your Account has been Modified. <a href="index.php">Return to Index</a></div>';
    }

    if ($_GET['entity'] == "account") {
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Modify</title>
    </head>
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
                        <a href="index.php" style="text-decoration: none; color: white;">Cancel</a>
                    </button>
                    <button type="submit" class="btn btn-primary" name="modifyaccount">Modify</button>
                
                </form>
            </div>
        </div>

<?php
    }
?>