<?php
session_start();
include('../functions.php');
if (empty($_SESSION['user_id'])){
    header('location:home.php');
}

if (!(isset($_SESSION['admin'])) && !($_SESSION['admin'])){
    header('location:home.php');
}

if (!empty($_SESSION['firstLogon'])) {
    if ($_SESSION['firstLogon'] == 1) {
        header('location:change_password.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $formID = $_POST['form_id'];
    if (isset($_POST['submit'])) {
        switch ($formID){
            case 'deleteUser':
                $userId = (int) usernameToID($_POST['deleteUserMenu']);
                try{
                    deleteUser($userId);
                    $_SESSION['update_message_type'] = "success";
                    $_SESSION['update_message'] = "User Deleted!";
                    logEvent('A user has been made inactive');
                    header('location: admin.php');
                }catch (Exception $e){
                    echo 'There was an error '. $e;
                    $_SESSION['update_message_type'] = "danger";
                    $_SESSION['update_message'] = "User failed to delete!";
                    header('location: admin.php');
                }
                break;
            case 'addUser':
                $userId = (int) usernameToID($_POST['addUserMenu']);
                try{
                    addUser($userId);
                    $_SESSION['update_message_type'] = "success";
                    $_SESSION['update_message'] = "User Added!";
                    logEvent('A user has been reinstated');
                    header('location: admin.php');
                    exit();
                }catch (Exception $e){
                    echo 'There was an error '. $e;
                    $_SESSION['update_message_type'] = "danger";
                    $_SESSION['update_message'] = "User failed to be added!";
                    header('location: admin.php');
                }
                break;
            case 'modifyUser':
                if (isset($_POST['user_id']) && isset($_POST['permission_level'])) {
                    $userId = (int) usernameToID($_POST['user_id']);
                    $newPermissionLevel = (int) $_POST['permission_level'];
                    try{
                        update_permission($newPermissionLevel, $userId);
                        $_SESSION['update_message_type'] = "success";
                        $_SESSION['update_message'] = "Permissions updated successfully!";
                        header('location: admin.php');
                        exit();
                    }catch(Exception $e){
                        echo 'There was an error '. $e;
                        $_SESSION['update_message_type'] = "danger";
                        $_SESSION['update_message'] = "Permissions failed to update!";
                        header('location: admin.php');
                        exit();
                    }
                }
                break;
        }

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <a class="nav-link" href="register.php">Register User</a>
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
                    <form method="POST">
                        <div class="form-group mb-3">
                            <select class="form-select" aria-label="Select option" id="deleteUserMenu" name="deleteUserMenu">
                                <?php
                                $users = active_users();
                                foreach($users as $user){
                                    echo '<option value="'.$user.'">'.$user.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="form_id" value="deleteUser">
                        <button type="submit" class="btn btn-primary" name="submit">Delete User</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Add User</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group mb-3">
                            <select class="form-select" aria-label="Select option" id="addUserMenu" name="addUserMenu">
                                <?php
                                $users = inactive_users();
                                foreach($users as $user){
                                    echo '<option value="'.$user.'">'.$user.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="form_id" value="addUser">
                        <button type="submit" class="btn btn-primary" name="submit">Add User</button>
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
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <input type="hidden" name="form_id" value="modifyUser">
                <button type="submit" class="btn btn-primary" name="submit">Update Permissions</button>
            </form>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>

