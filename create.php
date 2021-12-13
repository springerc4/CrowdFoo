<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    if (!isset($_SESSION['logged'])) {
        header('location: index.php');
    }
    //is admin
    
    $create_sql = new SqlOperation($db);

    $query = $db->prepare('SELECT * FROM projects ORDER BY project_ID DESC LIMIT 1');
    $query->execute();
    $projectID = ($query->fetch())['project_ID']+1;

    if (isset($_POST['name'])){
        $create_sql->createProject($_POST['name'],$_POST['description'],$_POST['goal'],$_POST['category']);
        for($i=1; $i<4; $i++){
            $create_sql->addReward($_POST['rewardName1'], $_POST['rewardPrice1'], $_POST['rewardDescription1'], $projectID);
        }
        ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Project Created.
            <a href="index.php"></a>
        </div>
        <?php
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="form" style="margin: auto;">
        <form method = "post">
            <div>
                <label for="name">Project Name:</label><br>
                <input type="text" id="name" name="name">
            </div>
            <div>
                <label for="description">Project description:</label><br>
                <textarea name="description" id="description" cols="30" rows="5"></textarea>
            </div>
            <div>
                <label for="goals">Project goal amount:</label><br>
                <div class="input-group mb-3 w-25">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" name="goal" id="goal" aria-label="Amount (to the nearest dollar)">
                        </div>
            </div>
            <div>
                <label for="category">Choose appropiate catagory:</label><br>
                <select name="category" id="category">
                    <?php
                        $result = $db->query('SELECT category_name FROM categories');
                        while($row = $result->fetch()){
                    ?>
                    <option value="<?=$row['category_name']?>"><?=$row['category_name']?></option>
                    <?php } ?>
                </select>
            </div>
            <?php 
            for($i=1; $i<4; $i++){
            ?>
            <div class="row p-3">
                reward name: 
                <input  type="<?=$i?>" name="rewardName<?=$i?>" id="rewardName<?=$i?>">
                reward description:
                <textarea name="rewardName<?=$i?>" id="rewardName<?=$i?>" cols="30" rows="5"></textarea>
                reward price
                <input type="<?=$i?>" name="rewardName<?=$i?>" id="rewardName<?=$i?>">
            </div>
            <?php } ?>
            <button type="submit" class="btn btn-primary" style="margin-top: 2%;">Submit</button>
        </form>
    </div>
</body>
</html>