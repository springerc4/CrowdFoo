<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');

    session_start();
    if (!isset($_SESSION['logged'])) {
        $_SESSION['logged'] = 'false';
    }

    if ($_SESSION['logged'] == "false") {
        setDefaultSession();
    }
    else {
        $query = $db->prepare('SELECT isAdmin, user_password, first_name, last_name, user_ID FROM users WHERE email = ?');
        $query->execute([$_SESSION['email']]);
        $row = $query->fetch();
        $_SESSION['userID'] = $row['user_ID'];
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>CrowdFoo</title>
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
                <?php
                    if ($_SESSION['logged'] == 'false') {
                ?>
                <li class="nav-item">
                    <button type="button" class="btn btn-light">
                        <a href="authentication.php?auth=login" style="text-decoration:none; color: black;">Sign In</a>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-light">
                        <a href="authentication.php?auth=register" style="text-decoration:none; color: black;">Sign Up</a>
                    </button>
                </li>
                <?php
                    } else {
                ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-light">
                                <a href="authentication.php?auth=logout" style="text-decoration:none; color: black;">Sign Out</a>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-light">
                                <a href="delete.php?entity=account" style="text-decoration: none; color: black;">Delete Account</a>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-light">
                                <a href="modify.php?entity=account" style="text-decoration: none; color: black;">Modify Account</a>
                            </button>
                        </li>
                <?php
                    }

                ?>
            </ul>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>