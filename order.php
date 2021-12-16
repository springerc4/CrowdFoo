<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');

    session_start();

    $order_sql = new SqlOperation($db);

    $project_id = $_GET['projectid'];

    $address_info = $order_sql->addressInfo($_SESSION['userID']);

    if (isset($_POST['confirmorder'])) {
        $order_sql->placeOrder(date("Y-m-d"), date("Y-m-d"), $_SESSION['userID']);
        $order_sql->addMoney($_GET['contribute'], $project_id);
        $order_sql->newContributor($_GET['contribute'], $_SESSION['userID'], $project_id);
        echo '<div class="alert alert-success" role="alert">Your order has been placed. <a href="project.php?projectid='.$project_id.'">Return to Project</a></div>';
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Confirm Order</title>
    </head>
    <body>
    <div class="container">
        <h3> Confirm Your Order</h3>
        <br><br>
        <div class="row align-items-start p-3">
            <div class="col">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Date Ordered: </h5>
                        <p>
                            <?= date("Y-m-d") ?>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5>Project supported:</h5>
                        <p><?=$order_sql->projectName($project_id)['project_name'];?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Date Fulfilled: </h5>
                        <p>
                            <?= date("Y-m-d") ?>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5>Amount:</h5>
                        <p>$<?=$_GET['contribute']?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <?php
                    if ($address_info != null) {
                ?>
                <div class="card p-3" style="width: 18rem;">
                    <h5>Shipping Address: </h5>
                    <button type="button" class="btn btn-primary"><a href="modify.php?entity=address" style="text-decoration: none; color: white;">Edit Address</a></button>
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
                    } else {
                ?>
                <div class="card">
                    <div class="card-body">
                        <h5>Shipping Address: </h5>
                        <button type="button" class="btn btn-primary"><a href="create.php?entity=address" style="text-decoration: none; color: white;">Add Address</a></button>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <?php
                if ($address_info != null) {
            ?>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <button type="button" class="btn btn-secondary">
                                <a href="project.php?projectid=<?= $project_id ?>" style="text-decoration: none; color: white;">Cancel</a>
                            </button>
                            <button type="submit" class="btn btn-primary" name="confirmorder">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    </body>
</html>