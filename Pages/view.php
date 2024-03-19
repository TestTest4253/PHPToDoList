<?php
session_start();
include('../functions.php');

if (!empty($_SESSION['firstLogon'])) {
    if ($_SESSION['firstLogon'] == 1) {
        header('location:change_password.php');
    }
}

if (empty($_SESSION['user_id'])){
    header('location:home.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = (int)$_POST['task_id'];
    if (isset($_POST['deleteButton'])) {
        delete_task($taskId);
        logEvent('Task deleted');
        header('refresh:0.1');
    }
}
?>
<!DOCTYPE html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #f0f0f0; /* Softer background */
        }
        .navbar-brand {
            padding: 20px;
        }
        .button-container{
            display: flex;
            justify-content: space-between;
        }
        .username-container {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 14px;
        }
    </style>
    <title>30061640</title>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="home.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php
            if (isset($_SESSION['user_id'])){
                if (empty($_SESSION['guest'])){
                    echo '
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Create Task</a>
                </li>';}
                echo '
                <li class=nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>';
            } else{
                echo '
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                ';
            }
            ?>


        </ul>
    </div>
</nav>
<br>
<h2 style="text-align: center">Current Tasks</h2>
<div class="container">
    <div class="row">
        <?php
        $tasks = collect_tasks();
        $length = count($tasks);
        for ($x = 0; $x<$length; $x++) {
            $task = $tasks[$x];
            $username = IDtoUsername($task[0]);
            $title = $task[2];
            $contents = $task[3];
            $rawDate = $task[4];
            $due_date = date('l d F Y',strtotime($task[4]));
            $status = $task[6];
            echo '<div class="col-sm-4">
            <div class="card h-100 shadow-sm'; if($status == 'Done'){echo ' bg-success';} elseif($status == 'Backlog'){echo ' bg-warning';} echo '">
                <div class="card-body">
                    <div class="username-container">' . $username . '</div>
                    <h5 class="card-title">' . $title .' - '.$status . '</h5>
                    <p class="card-text">' . $contents . '</p>
                    <p class="card-text">' . $due_date . '</p>';
            if (empty($_SESSION['guest'])){
                echo '
            <div class="button-container"> 
            <form method="post" action="edit.php">
            <input type="hidden" name="task_id" value="'.$task[1].'">
            <input type="hidden" name="title" value="'.$title.'">
            <input type="hidden" name="contents" value="'.$contents.'">
            <input type="hidden" name="date" value="'.$rawDate.'">
            <input type="hidden" name="status" value="'.$status.'">
            <button type="submit" name="editButton" class="btn btn-primary">Edit</button>
            </form>
            <form method="post" action="">
            <input type="hidden" name="task_id" value="'.$task[1].'">
            <button type="submit" name="deleteButton" class="btn btn-danger">Delete</button>
            </form>
            </div>
            ';}
            echo '</div> </div> </div>';
        }
            ?>
    </div>
</div>
<br>
<h2 style="text-align: center;">All Tasks</h2>
<div class="container">
    <div class="row">
        <?php
        $tasks = all_tasks();
        $length = count($tasks);
        if ($length > 0) {
            for ($x = 0; $x < $length; $x++) {
                $task = $tasks[$x];
                $username = IDtoUsername($task[0]);
                $title = $task[2];
                $contents = $task[3];
                $due_date = date('l d F Y',strtotime($task[4]));
                $rawDate = $task[4];
                $completed = $task[5];
                $status = $task[6];
                echo '<div class="col-sm-4">
            <div class="card h-100 shadow-sm'; if($status == 'Done'){echo ' bg-success';} elseif($status == 'Backlog'){echo ' bg-warning';} echo '">
                <div class="card-body">
                    <div class="username-container">' . $username . '</div>
                    <h5 class="card-title">' . $title .' - '.$status . '</h5>
                    <p class="card-text">' . $contents . '</p>
                    <p class="card-text">' . $due_date . '</p>
            ';
            if (isset($_SESSION['admin'])) {
                echo '
                <div class="button-container"> 
                <form method="post" action="edit.php">
                <input type="hidden" name="task_id" value="'.$task[1].'">
                <input type="hidden" name="title" value="'.$title.'">
                <input type="hidden" name="contents" value="'.$contents.'">
                <input type="hidden" name="date" value="'.$rawDate.'">
                <input type="hidden" name="status" value="'.$status.'">
                <button type="submit" name="editButton" class="btn btn-primary">Edit</button>
                </form>
                <form method="post" action="">
                <input type="hidden" name="task_id" value="'.$task[1].'">
                <button type="submit" name="deleteButton" class="btn btn-danger">Delete</button>
                </form>
                </div>';
            }
                echo '</div> </div> </div>
            ';
            }
        } else{
            echo '<br>';
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
