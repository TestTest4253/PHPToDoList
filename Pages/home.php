<?php
session_start();
include('../functions.php');
if (!empty($_SESSION['firstLogon'])) {
    if ($_SESSION['firstLogon'] == 1) {
        header('location:change_password.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>30061640</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .navbar-brand{
            padding: 20px;
        }
        body {
            background: #f0f0f0; /* Softer background */
        }
        .video-container{
            display:flex;
            justify-content: center;
        }
    </style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php
            if (empty($_SESSION['user_id'])){
                echo '
                
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>';
            }else{
                echo'
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Create Task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view.php">View Tasks</a>
                </li>';
                if (!empty($_SESSION['admin'])) {
                    echo '<li class="nav-item"> <a class="nav-link" href="admin.php">Admin</a>';
                };
                echo'
                <li class=nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
                ';

            }
            ?>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 style="text-align: center">Welcome<?php if (!empty($_SESSION['user_id'])){echo ' '.IDtoUsername($_SESSION['user_id']);} ?>!</h1>
    <?php
    if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == 14){
        echo '<p class = "video-container"><iframe width="560" height="315" src="https://www.youtube.com/embed/MtN1YnoL46Q" title="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"  allowFullScreen></iframe></p>';
    }
    echo '<pre>' . var_dump($_SESSION) . '</pre>'; ?>
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>