<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    
    $project_sql = new SqlOperation($db);

    $id = $_GET['projectid'];

    $project = $project_sql->getProject($id);

    $rewards = $project_sql->getRewards($id);

    $canPay = 'disabled';
    if ($_SESSION['logged']) {
        $canPay = null;
    }


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
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:50%">$500</div>
                    </div>
                </div>
                <div class="container mt-5">
                    <h3>Click one of the tier options to support this project!</h3>
                </div>
                <div class="container p-3">
                    <?php
                        $i = 0;
                        foreach ($rewards as $r){
                            $i++;     
                    ?>
                        <h5>tier <?=$i?>:</h5>
                        <h6>Reward:</h6>
                        <button type="button" class="btn btn-outline-primary <?=$canPay?>" data-bs-toggle="modal" data-bs-target="#rewardDesc<?=$i?>"><?=$r['reward_name']?></button>
                        <button class="btn btn-success m-3">
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>