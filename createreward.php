<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();
    
    $reward_sql = new SqlOperation($db);

    $id = $_GET['projectid'];

    if (isset($_POST['name'])){
        if(is_numeric($_POST['goal'])){
            $reward_sql->addReward($_POST['name'],$_POST['goal'],$_POST['description'],$id);

            ?>
            <div class="alert alert-success">
                <strong>Success!</strong> reward added. Add another or go back to the <a href="project.php?projectid=<?=$id?>">project</a>
                
            </div>
            
            <?php
        }
        else{
            ?>
            <div class="alert alert-danger">
                <strong>Error</strong> reward amount must be an integer.
                <a href="index.php"></a>
            </div>
        <?php
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>add rewards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
    <div class="container mt-3">
        <h3>Add a reward</h3>
    </div>
    <div class="container p-3 m-auto">
        <form method="post">
            <label for="name">reward name:</label><br>
            <input type="text" id="name" name="name"><br>
            <label for="description">reward description:</label><br>
            <textarea name="description" id="description" cols="30" rows="3"></textarea><br>
            <label for="goal">reward amount:</label><br>
            <div class="input-group mb-3 w-25">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="goal">
                </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 2%;">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>