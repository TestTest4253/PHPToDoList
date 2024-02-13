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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        .navbar-brand{
            padding: 20px;
        }
        body {
            background: darkslategray;
        }
        .dropdown-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: 45%;
            margin: 0 auto;
        }
        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
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

<div class="container">
    <div class="dropdown-container">
        <legend style="margin-left: auto; margin-right: auto;">Delete User</legend>
        <form action="" method="post">
            <label for="deleteUserMenu" class="form-label">Select Option:</label>
            <select class="form-select" aria-label="Select option" id="deleteUserMenu" name="deleteUserMenu">
                <option value="">Choose...</option>
                <?php
                $users = active_users();
                foreach($users as $user){
                    echo '<option value="'.$user.'">'.$user.'</option>';
                }
                ?>
            </select>
            <input type="hidden" name="form_id" value="deleteUser">
            <button type="submit" class="btn btn-primary mt-3" name="submit">Submit</button>
        </form>
    </div>

    <div class="dropdown-container">
        <legend style="margin-left: auto; margin-right: auto;">Add User</legend>
        <form action="" method="post">
            <label for="addUserMenu" class="form-label">Select Option:</label>
            <select class="form-select" aria-label="Select option" id="addUserMenu" name="addUserMenu">
                <option value="">Choose...</option>

            </select>
            <input type="hidden" name="form_id" value="addUser">
            <button type="submit" class="btn btn-primary mt-3" name="submit">Submit</button>
        </form>
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
                $users = active_users();
                echo implode(" ",$users);
                break;
            case 'addUser':
                $username = $_POST['addUserMenu'];
                echo $username;
                break;
        }

    }
}
?>