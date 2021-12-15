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

    $index_query = $db->query('SELECT * FROM projects ORDER BY project_ID DESC LIMIT 3');

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
                                if ($_SESSION['admin'] == 1) {
                            ?>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-light">
                                        <a href="create.php?entity=category" style="text-decoration: none; color: black;">Create Category</a>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-light">
                                        <a href="create.php?entity=project" style="text-decoration: none; color: black;">Create Project</a>
                                    </button>
                                </li>
                                <?php
                                }
                                ?>
                    <?php
                        }

                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="card" style="width: 18rem; margin-left: 40%;">
        <div class="card-header">
            New Projects
        </div>
        <ul class="list-group list-group-flush">
            <?php
                while ($index_row = $index_query->fetch()) {
                    $category_query = $db->prepare('SELECT category_name FROM categories WHERE category_ID = ?');
                    $category_query->execute([$index_row['category_ID']]);
                    $category_row = $category_query->fetch();
            ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $index_row['project_name'] ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Category: <?php echo $category_row['category_name']  ?></h6>
                <a href="project.php?projectid=<?php echo $index_row['project_ID'] ?>" class="card-link">View Project</a>
            </div>
            <?php
                }
            ?>
        </ul>
    </div>
    

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>