<?php
session_start();
if (!empty($_SESSION['firstLogon'])) {
    if ($_SESSION['firstLogon'] == 1) {
        header('location:change_password.php');
    }
}
?>

<?php
$server_name = 'localhost';
$sql_user = 'webapp_insert';
$sql_pass = 'TE1rrJ0M4tKD!x4I';

if (isset($_POST['submit'])){
    $username = $_POST['username'];
    $forename = $_POST['fname'];
    $surname = $_POST['sname'];
    $email = $_POST['email'];
    $password = 'Cyb3rS3cur1ty!';
    $conn = new mysqli($server_name, $sql_user, $sql_pass);
    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    $sql = 'INSERT INTO credentialsbt.methodone(username,forename,surname,email,password) VALUES (?,?,?,?,?)';
    try{
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param('sssss', $username, $forename, $surname, $email, $hashed_password);
    }catch(Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $_SESSION['update_message_type'] = "success";
        $_SESSION['update_message'] = "User registered";
    }else{
        $_SESSION['update_message_type'] = "danger";
        $_SESSION['update_message'] = "Something went wrong!";
    }
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
    <script>
        if (<?php echo isset($_SESSION['update_message']); ?>) {
            setTimeout(function() {
                window.location.href = "admin.php";
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
            <li class=nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<br>
<h2 style="text-align: center">Registration Form</h2>
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
                    <label for="fname" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="fname" name="fname" value="" required>
                </div>
                <div class="mb-3">
                    <label for="sname" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="sname" name="sname" value="" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="" required>
                </div>
                <input type="submit" class = 'btn btn-primary' value="Submit" name="submit">
            </form>
        </div>
    </div>
</div>
</body>