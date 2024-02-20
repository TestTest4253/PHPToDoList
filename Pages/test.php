<?php
session_start();
include('../functions.php');
if (empty($_SESSION['user_id']) && !($_SESSION['admin'])){
    header('location:home.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f0f0f0; /* Softer background */
        }

        .navbar-brand {
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 10px;
        }
    </style>
    <title>30061640</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="./home.php">Home</a>
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

<div class="container mt-4">

    <?php if (isset($_SESSION['update_message'])) { ?>
        <div class="alert alert-<?php echo $_SESSION['update_message_type']; ?>" role="alert">
            <?php echo $_SESSION['update_message']; ?>
        </div>
        <?php
        unset($_SESSION['update_message']);
        unset($_SESSION['update_message_type']);
    } ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Delete User</div>
                <div class="card-body">
                    <form action="" method="post">
                        <select class="form-select" aria-label="Select option" id="deleteUserMenu" name="deleteUserMenu">
                            <option value="">Choose...</option>
                            <?php
                            $users = active_users();
                            foreach($users as $user){
                                echo '<option value="'.$user.'">'.$user.'</option>';
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Add User</div>
                <div class="card-body">
                    <form action="" method="post">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Modify User Permissions</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="user_select">Select User:</label>
                    <select class="form-select" id="user_select" name="user_id">
                        <?php
                        $users = active_users();
                        foreach ($users as $user){
                            echo '<option value="'.$user.'">'.$user.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="permission_level">Permission Level:</label>
                    <select class="form-select" id="permission_level" name="permission_level">
                        <option value="1">Guest</option>
                        <option value="2">User</option>
                        <option value="3">Admin</option>
                    </select>
                </div>
                <input type="hidden" name="form_id" value="modifyUser">
                <button type="submit" class="btn btn-primary" name="submit">Update Permissions</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $formID = $_POST['form_id'];
    if (isset($_POST['submit'])) {
        switch ($formID){
            case 'deleteUser':
                $username = $_POST['deleteUserMenu'];
                break;
            case 'addUser':
                $username = $_POST['addUserMenu'];
                echo $username;
                break;
            case 'modifyUser':
                if (isset($_POST['user_id']) && isset($_POST['permission_level'])) {
                    $userId = (int) usernameToID($_POST['user_id']);
                    $newPermissionLevel = (int) $_POST['permission_level'];
                    update_permission($newPermissionLevel, $userId);
                    $_SESSION['update_message_type'] = "success";  // For a success alert
                    $_SESSION['update_message'] = "Permissions updated successfully!";
                }
                break;
        }

    }
}
?>