<?php
require_once('settings.php');
require_once('sqlfunctions.php');

session_start();

$account_sql = new SqlOperation($db);

$account_info = $account_sql->accountInfo($_SESSION['userID']);
$address_info = $account_sql->addressInfo($_SESSION['userID']);
$supportedProjects = $account_sql->getUsersSupportedProjects($_SESSION['userID']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Account Info</title>
    </head>
    <body>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Account</li>
            </ol>
        </nav>

        <div class="container">
            <div class="row align-items-start">
                <div class="col">
                    <br><br>
                    <button type="button" class="btn btn-primary"><a href="modify.php?entity=account" style="text-decoration: none; color: white;">Modify</a></button>
                    <button type="button" class="btn btn-primary"><a href="delete.php?entity=account" style="text-decoration: none; color: white;">Delete</a></button>
                    <br><br>
                    <div class="card" style="width: 18rem;">
                        <div class="card-header">
                            Account Info
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h5>Email Address:</h5>
                                <p><?php echo $account_info['email'] ?></p>
                            </li>
                            <li class="list-group-item">
                                <h5>First Name:</h5>
                                <p><?php echo $account_info['first_name'] ?></p>
                            </li>
                            <li class="list-group-item">
                                <h5>Last Name:</h5>
                                <p><?php echo $account_info['last_name'] ?></p>
                            </li>
                            <li class="list-group-item">
                                <h5>Admin Status:</h5>
                                <p><?php 
                                    if ($account_info['isAdmin'] == 0) {
                                        echo 'No';
                                    } else echo 'Yes';
                                ?></p>
                            </li>
                            <li class="list-group-item">
                                <h5># of Projects Managed:</h5>
                                <p><?php echo $account_info['projects_managed'] ?></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col">
                    <?php
                        if (!$account_sql->containsAddress($_SESSION['userID'])) {
                    ?>
                            <button type="button" class="btn btn-primary" style="margin-top: 10%;"><a href="create.php?entity=address" style="text-decoration: none; color: white;">Add Shipping Address</a></button>
                    <?php
                        } else {
                    ?>
                            <h5>Shipping Address: </h5>
                            <button type="button" class="btn btn-primary" style="margin-top: 3%;"><a href="modify.php?entity=address" style="text-decoration: none; color: white;">Edit Address</a></button>
                            <button type="button" class="btn btn-primary" style="margin-top: 3%;"><a href="delete.php?entity=address" style="text-decoration: none; color: white;">Delete Address</a></button>
                            <br><br>
                            <div class="card" style="width: 18rem;">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <h5>City: </h5>
                                        <p><?php echo $address_info['city'] ?></p>
                                    </li>
                                    <li class="list-group-item">
                                        <h5>State/Province: </h5>
                                        <p><?php echo $address_info['_state'] ?></p>
                                    </li>
                                    <li class="list-group-item">
                                        <h5>Country: </h5>
                                        <p><?php echo $address_info['country'] ?></p>
                                    </li>
                                    <li class="list-group-item">
                                        <h5>Zipcode: </h5>
                                        <p><?php echo $address_info['zipcode'] ?></p>
                                    </li>
                                </ul>
                            </div>
                    <?php
                        }
                    ?>
                </div>
                <div class="col mt-5">
                    <div class="card">
                        <div class="card-header">
                            Projects you have supported
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php
                            foreach($supportedProjects as $p){
                               $name = $account_sql->projectName($p['project_ID']);
                            ?>
                            <li class="list-group-item">
                                <a href="projects.php?projectid=<?=$p['project_ID']?>"><?=$name?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>



