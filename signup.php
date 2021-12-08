<?php
    require_once('settings.php');
    require_once('authentication.php');

    session_start();
    if ($_SESSION['logged'] == "true") {
        //header('Location: index.php');
    }

    if (count($_POST) > 0) {
        if ($_GET['action'] == 'register') {
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
                signup($db, $_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname']);
            }
        }
        else if ($_GET['action'] == 'login') {
            if (!isset($_POST['email'])) {
                echo '<div class="alert alert-warning" role="alert">Please Enter an Email Address.</div>';
                die();
            }
            else if (!isset($_POST['password'])) {
                echo '<div class="alert alert-warning" role="alert">Please Enter a Password</div>';
                die();
            }
            else {
                signin($db, $_POST['email'], $_POST['password']);
            }
        }
        else {
            signout();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Sign Up</title>
    </head>

    <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">CrowdFoo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#signinmodal">
                        Sign In
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#signupmodal">
                        Sign Up
                    </button>
                </li>
                <?php
                    if ($_SESSION['logged']) {
                ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#signoutmodal">
                                Sign Out
                            </button>
                        </li>
                <?php
                    }

                ?>
            </ul>
        </div>
    </div>

    <!-- Sign Up Modal -->
    <div class="modal fade" id="signupmodal" tabindex="-1" aria-labelledby="signupmodallabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="signup.php?action=register">
                    <div class="modal-body">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>  
    
    <!-- Sign In Modal -->
    <div class="modal fade" id="signinmodal" tabindex="-1" aria-labelledby="signinmodallabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="signup.php?action=login">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    

    <!-- Sign Out Modal -->
    <div class="modal fade" id="signoutmodal" tabindex="-1" aria-labelledby="signoutmodallabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign Out?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="signup.php?action=logout">
                    <div class="modal-body">
                        Are you sure you want to sign out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Sign Out</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>