<?php
session_start();
session_unset();
include('../functions.php');
?>

<?php
$server_name = 'localhost';
$sql_user = 'webapp_select';
$sql_pass = 'P_k(x[1!gDObxh7-';

$conn = new mysqli($server_name, $sql_user, $sql_pass);
if ($conn-> connect_error){
    die('Connection Failed: '.$conn->connect_error);
}
if (isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['pword'];
    $sql = 'SELECT user_id,password from credentialsbt.methodone WHERE username = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
    }catch(Exception $e){
        echo 'Your error is '.$e;
    }

    if($stmt->execute()){
        $result = $stmt->get_result();
        $array = $result->fetch_assoc();
        $storedPassword = $array['password'];
        $user_id = $array['user_id'];
        if (password_verify($password, $storedPassword)){
            $_SESSION['user_id'] = $user_id;
            if (is_admin($user_id) == 1){
                $_SESSION['admin'] = True;
            }
            if (firstLogon($user_id)){
                $_SESSION['firstLogon'] = 1;
            }
            $_SESSION['update_message_type'] = "success";
            $_SESSION['update_message'] = "Enjoy!";
        } else{
            $_SESSION['update_message_type'] = "danger";
            $_SESSION['update_message'] = "Login Failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>30061640</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .navbar-brand{
            padding: 20px;
        }
        body {
            background: #f0f0f0; /* Softer background */
        }
    </style>

    <script>
        if (<?php echo !empty($_SESSION['update_message']); ?> && <?php if (isset($_SESSION['firstLogon'])){echo $_SESSION['firstLogon'];} else {echo 0;}?>)
        {
            if (<?php echo $_SESSION['update_message'] == 'Enjoy!'; ?>) {
            setTimeout(function () {
                window.location.href = "change_password.php";
            }, 1000);
            }
        } else{
            setTimeout(function() {
                window.location.href = "home.php";
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
</nav>
<br>
<h2 style="text-align: center">Login Form</h2>
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
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="" required>
                </div>
                <div class="mb-3">
                    <label for="pword" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="pword" name="pword" value="" required>
                </div>
                <input type="submit" class='btn btn-primary' value="Submit" name="submit">
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

