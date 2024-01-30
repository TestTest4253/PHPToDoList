<?php
session_start();
session_unset();
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
<h2>Login Form</h2>

<form action="" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="">
    </div>
    <div class="mb-3">
        <label for="pword" class="form-label">Password:</label>
        <input type="password" class="form-control" id="pword" name="pword" value="">
    </div>
    <input type="submit" class='btn btn-primary' value="Submit" name="submit">
</form>
</body>
</html>
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
        $array = $result->fetch_assoc();//['password'];
        $storedPassword = $array['password'];
        $user_id = $array['user_id'];
        if (password_verify($password, $storedPassword)){
            echo '<p>Welcome '.$username.'</p>';
            $_SESSION['user_id'] = $user_id;
            header('location:home.php');
        }
    }
}

