<?php
session_start();
include('../functions.php');
if (empty($_SESSION['user_id'])){
    header('location:home.php');
}
?>

<!DOCTYPE html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background: #f0f0f0; /* Softer background */
        }
        .navbar-brand {
            padding: 20px;
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
            <li class="nav-item">
                <a class="nav-link" href="view.php">View Tasks</a>
            </li>
            <li class=nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<br>
<div class="card">
    <div class="card-header">
        Create a Task
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label for="taskTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="taskTitle" name="taskTitle" required>
            </div>
            <div class="mb-3">
                <label for="taskContent" class="form-label">Contents</label>
                <textarea class="form-control" id="taskContent" name="taskContent" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="dueDate" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="dueDate" name="dueDate" required>
            </div>
            <div class="mb-3">
                <label for="user" class="form-label">User</label>
                <select class="form-select" aria-label="Select option" id="user" name="user">
                    <option value="">Choose...</option>
                    <?php
                    if ($_SESSION['admin']){
                        $users = active_users();
                        foreach($users as $user){
                            echo '<option value="'.$user.'">'.$user.'</option>';
                        }
                    } else{
                        echo '<option value="'.IDtoUsername($_SESSION['user_id']).'">'.IDtoUsername($_SESSION['user_id']).'</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
</body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['submit'])) {
        $title = $_POST['taskTitle'];
        $content = $_POST['taskContent'];
        $dueDate = date('Y-m-d', strtotime($_POST['dueDate']));
        $user = usernameToID($_POST['user']);
        $success = create_task($user, $title, $content, $dueDate);
        if ($success){
            echo 'Task created';
        } else{
            echo 'Task failed';
        }
    }
}
?>