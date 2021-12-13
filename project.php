<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();
    
    $project_sql = new SqlOperation($db);

    $id = $_GET['projectid'];

    $project = $project_sql->getProject($id);

    $rewards = $project_sql->getRewards($id);

    $canPay = 'disabled';
    if ($_SESSION['logged']) {
        $canPay = null;
    }

    $goalRatio = ($project['money_collected']/$project['project_goal'])*100;




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$project['project_name']?></title>
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
    <div class="container shadow-sm p-4 mb-4">
        <div class="container mb-5">
            <h1><?=$project['project_name']?></h1>
        </div>
        <div class="container">
            <div class="col">
                <div class="container">
                    <h3>Description:</h3><br>
                    <p><?=$project['project_description']?></p>
                </div>
                <div class="container">
                    <h3>Number of Backers:</h3>
                    <p><?=$project['number_of_backers']?></p>
                </div>
                <div class="container">
                    <h4>Goal:<?=$project['project_name']?></h4>
                    <div class="progress w-50">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:<?=$goalRatio?>%">$<?=$project['money_collected']?></div>
                    </div>
                </div>
                <div class="container mt-5">
                    <h3>Contribute to this project:</h3>
                    <form method='post'>
                        <div class="input-group mb-3 w-25">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                        </div>
                        <div class="container">
                            <button class="btn btn-primary m-3">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <div class="container mt-5">
                    <h3>Rewards you can gain based on your support!</h3>
                </div>
                <div class="container p-3">
                    <?php
                        $i = 0;
                        foreach ($rewards as $r){
                            $i++;     
                    ?>
                        <h5>tier <?=$i?>:</h5>
                        <h6>Reward:</h6>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rewardDesc<?=$i?>"><?=$r['reward_name']?></button>
                        <button class="btn btn-success m-3 disabled">
                            <span class="spinner-grow spinner-grow-sm"></span>
                            $<?=$r['reward_price']?>
                        </button>

                        <div class="modal" id="rewardDesc<?=$i?>">
                        <div class="modal-dialog">
                            <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title"><?=$r['reward_name']?></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <?=$r['reward_description']?>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        if($_SESSION['isAdmin'] && $_SESSION['userID']==$project['user_ID']){
    ?>
    <div class="container p-3">
        <button type="button" class="btn btn-info"><a href="modify.php?entity=project&projectid=<?=$id?>" style="text-decoration: none; color: white;">Modify</a></button>
        <button type="button" class="btn btn-danger"><a href="delete.php?entity=project&projectid=<?=$id?>" style="text-decoration: none; color: white;">Delete</a></button>
    </div>
    <?php
        }
    ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>