<?php
    require_once('settings.php');
    require_once('sqlfunctions.php');
    session_start();

    if (!isset($_SESSION['logged'])) {
        header('location: index.php');
    }
    //is admin
    
    $create_sql = new SqlOperation($db);

    if (isset($_POST['name'])){
        $create_sql->createProject($_POST['name'],$_POST['description'],$_POST['goal'],$_POST['category']);
        ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Project Created.
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
                <input type="text" name="goal" id="goal" value="$">
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
            <button type="submit" class="btn btn-primary" style="margin-top: 2%;">Submit</button>
        </form>
    </div>
</body>
</html>