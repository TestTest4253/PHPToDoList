<?php
session_start();
include('../functions.php');
if (empty($_SESSION['user_id'])){
    header('location:home.php');
}
if (!empty($_SESSION['firstLogon'])) {
    if ($_SESSION['firstLogon'] == 1) {
        header('location:change_password.php');
    }
}

if (!empty($_SESSION['guest'])) {
    if ($_SESSION['guest'] == 1) {
        header('location:home.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['submit'])) {
        $title = sanitise($_POST['taskTitle']);
        $content = sanitise($_POST['taskContent']);
        $status = $_POST['status'];
        $dueDate = date('Y-m-d', strtotime($_POST['dueDate']));
        $user = usernameToID($_POST['user']);
        $success = create_task($user, $title, $content, $dueDate, $status);
        if ($success){
            $_SESSION['update_message_type'] = "success";
            $_SESSION['update_message'] = "Task created!";
            logEvent('New task created');
        } else{
            $_SESSION['update_message_type'] = "danger";
            $_SESSION['update_message'] = "Task failed to create!";
        }
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
    </style>
    <title>30061640</title>
    <script>
        if (<?php echo isset($_SESSION['update_message']); ?>) {
            setTimeout(function() {
                window.location.href = "view.php";
            }, 1000);
        }
    </script>
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
<div class="container mt-4">
    <?php if (isset($_SESSION['update_message'])) { ?>
        <div class="alert alert-<?php echo $_SESSION['update_message_type']; ?>" role="alert">
            <?php echo $_SESSION['update_message']; ?>
        </div>
        <?php
        unset($_SESSION['update_message']);
        unset($_SESSION['update_message_type']);
    } ?>
    <div class="card">
        <div class="card-header">
            Create a Task
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="taskTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="taskTitle" name="taskTitle" maxlength="64" required>
                </div>
                <div class="mb-3">
                    <label for="taskContent" class="form-label">Contents</label>
                    <textarea class="form-control" id="taskContent" name="taskContent" rows="3" maxlength="1500" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="dueDate" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="dueDate" name="dueDate" required>
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">User</label>
                    <select class="form-select" aria-label="Select option" id="user" name="user">
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
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" aria-label="Select option" id="status" name="status">
                        <option value="Backlog">Backlog</option>
                        <option value="Doing">Doing</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
