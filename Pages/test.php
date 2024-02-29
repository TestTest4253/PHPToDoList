<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .custom-bg {
            background-color: #FAF9F6;
        }
        body {
            padding-top: 30px;
        }
        .circular-container {
            background-color: black;
            border-radius: 1000%;
            padding: 100px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body class="custom-bg">
<form class="form-horizontal" action="index.php" method="post">
    <div class="circular-container">
        <h1 style="font-style: italic">Login to the 90's</h1>
        <div class="row">
            <div class="col-sm-6" style="background-color:blueviolet;">
                <div class="form-group">
                    <label class="control-label col-sm-20" for="inputusername">User-name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" style="background-color:darkolivegreen;">
                <div class="form-group">
                    <label for="pwd">Password</label>
                    <input type="Password" class="form-control" id="pwd" name="password" placeholder="Enter password">
                </div>
            </div>
        </div>
        <div style="padding-top: 5px">
            <button type="submit" class="btn btn-info" name="submit">Submit</button>
        </div>
    </div>
</form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
<?php

$server_name = 'localhost';
$sql_user = 'webapp_select';
$password = 'P_k(x[1!gDObxh7-';

$conn = new mysqli($server_name, $sql_user, $password);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['username']) && isset($_POST['password'])) {
    $stmt = $conn->prepare("SELECT * FROM credentials.methodone WHERE username = ?");
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $DB_username = $row['username'];
        $DB_password = $row['password'];

        if (password_verify($_POST['password'], $DB_password)) {
            echo 'Login Successful!';
        } else {
            echo "Login Unsuccessful1 - Invalid username or password";
        }
    } else {
        echo "Login Unsuccessful2 - Invalid username or password";
    }
}