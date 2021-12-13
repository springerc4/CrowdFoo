<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');

    $index_sql = new SqlOperation($db);

    session_start();
    if (!isset($_SESSION['logged'])) {
        $_SESSION['logged'] = 'false';
    }

    if ($_SESSION['logged'] == "false") {
        $index_sql->setDefaultSession();
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
                                    <a href="account.php" style="text-decoration: none; color: black;">View Account</a>
                                </button>
                            </li>
                    <?php
                        }

                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <button type="button" class="btn btn-light">
        <a href="project.php?projectid=1" style="text-decoration:none; color: black;">project</a>
    </button>
    <?php
    if ($_SESSION['logged'] == 'true' && $_SESSION['admin'] == 1) {
    ?>
        <div class="container" style="margin-top: 5%; margin-left: 0%;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <button type="button" class="btn btn-info"><a href="create.php?entity=category" style="text-decoration: none; color: white;">Create Category</a></button>
                </li>
                <br><br>
                <li class="nav-item">
                    <button type="button" class="btn btn-info"><a href="create.php?entity=project" style="text-decoration: none; color: white;">Create Project</a></button>
                </li>
            </ul>
        </div>
    <?php
        }
    ?>
    

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>