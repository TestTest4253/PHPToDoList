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

<h2>Registration Form</h2>

<form action="" method="post">
  <div class="mb-3">
    <label for="username" class="form-label">Username:</label>
    <input type="text" class="form-control" id="username" name="username" value="">
  </div>
  <div class="mb-3">
    <label for="fname" class="form-label">First Name:</label>
    <input type="text" class="form-control" id="fname" name="fname" value="">
  </div>
  <div class="mb-3">
    <label for="sname" class="form-label">Last Name:</label>
    <input type="text" class="form-control" id="sname" name="sname" value="">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <input type="text" class="form-control" id="email" name="email" value="">
  </div>
  <div class="mb-3">
    <label for="pword" class="form-label">Password:</label>
    <input type="password" class="form-control" id="pword" name="pword" value="">
  </div>
  <input type="submit" class = 'btn btn-primary' value="Submit" name="submit">
</form>

</body>
</html>
<?php
$server_name = 'localhost';
$sql_user = 'webapp_insert';
$sql_pass = 'TE1rrJ0M4tKD!x4I';

if (isset($_POST['submit'])){
    $username = $_POST['username'];
    $forename = $_POST['fname'];
    $surname = $_POST['sname'];
    $email = $_POST['email'];
    $password = $_POST['pword'];
    echo '<p> Running SQL Connection</p>';
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
    echo '<p>Your account has been created!</p>';
  }else{
    echo 'Whoops you are an issue!';
  }
}


