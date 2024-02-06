<?php
session_start();
?>

<!DOCTYPE html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background: darkslategray;
        }
    </style>
    <title>30061640</title>
</head>
<html>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<?php
if (empty($_SESSION['user_id'])){
    header('location:home.php');
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="home.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="view.php">View Tasks</a>
            </li>
            <li class=nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>

<h2>Current Tasks</h2>
<div class="container">
    <div class="row">
        <?php
        $server_name = 'localhost';
        $sql_user = 'webapp_select';
        $sql_pass = 'P_k(x[1!gDObxh7-';

        $conn = new mysqli($server_name, $sql_user, $sql_pass);
        if ($conn-> connect_error){
            die('Connection Failed: '.$conn->connect_error);
        }
        $user_id = $_SESSION['user_id'];
        $sql = 'SELECT Title, Contents, Due_Date, Completed FROM credentialsbt.tasks INNER JOIN credentialsbt.methodone ON credentialsbt.methodone.user_id = tasks.User_ID WHERE credentialsbt.methodone.user_id = ?;';
        try{
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_id);
        }catch(Exception $e){
            echo 'Your error is '.$e;
        }
        if ($stmt->execute()){
            $results = $stmt->get_result();
            $tasks = $results->fetch_all();
        }else{
            echo 'Error: SQL Statement not executed';
        }

        $length = count($tasks);
        for ($x = 0; $x<$length; $x++){
            $task = $tasks[$x];
            $title = $task[0];
            $contents = $task[1];
            $due_date = $task[2];
            $completed = $task[3];
            echo '<div class="col-sm-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">'.$title.'</h5>
                    <p class="card-text">'.$contents.'</p>
                    <p class="card-text">'.$due_date.'</p>';
            echo '
                    <a href="#" class="btn btn-primary">Link to Detail</a>
                    <a href="#" class="btn btn-primary">Delete</a>
                </div>
            </div>
        </div>';
        };
        ?>

    </div>
</div>
</body>
</html>
<?php

