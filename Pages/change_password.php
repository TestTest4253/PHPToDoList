<?php
session_start();
include('../functions.php');

if (isset($_POST['submit'])){
    $password = sanitise($_POST['pword']);
    $confirm = sanitise($_POST['pwordconfirm']);
    if ($password === $confirm) {
        if (checkPassword($_SESSION['user_id'], $_POST['pword'])) {
            $_SESSION['update_message_type'] = "danger";
            $_SESSION['update_message'] = "Password already in use";
        } else {
            if (preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,20}$/", $password)){
                if (submitPassword((int)$_SESSION['user_id'], $password)) {
                    $conn = connect_db('localhost', 'webapp_update', '*j8hBQt3@i-m7ynQ', 'credentialsbt');
                    $sql = 'UPDATE methodone set firstLogon = 0 WHERE user_id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $_SESSION['user_id']);
                    $stmt->execute();
                    $_SESSION['update_message_type'] = "success";
                    $_SESSION['update_message'] = "Password updated";
                    $_SESSION['firstLogon'] = 0;
                } else {
                    $_SESSION['update_message_type'] = "danger";
                    $_SESSION['update_message'] = "Password failed to update";
                }
            } else {
                $_SESSION['update_message_type'] = 'danger';
                $_SESSION['update_message'] = 'Password should contain:<br>
                                                 a digit<br>
                                                 a special character<br> 
                                                 a capital letter<br>
                                                 8-20 characters long';
            }
        }
    } else{
        $_SESSION['update_message_type'] = "danger";
        $_SESSION['update_message'] = "Mismatched passwords";
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
                window.location.href = "home.php";
            }, 5000);
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
<h2 style="text-align: center">Change your password</h2>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="pword" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="pword" name="pword" value="" required>
                </div>
                <div class="mb-3">
                    <label for="pwordconfirm" class="form-label">Re-type Password:</label>
                    <input type="password" class="form-control" id="pwordconfirm" name="pwordconfirm" value="" required>
                </div>
                <input type="submit" class='btn btn-primary' value="Submit" name="submit">
            </form>
        </div>
    </div>
    <?php if (isset($_SESSION['update_message'])) { ?>
        <div class="alert alert-<?php echo $_SESSION['update_message_type']; ?>" role="alert">
            <?php echo $_SESSION['update_message']; ?>
        </div>
        <?php
        unset($_SESSION['update_message']);
        unset($_SESSION['update_message_type']);
    } ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>