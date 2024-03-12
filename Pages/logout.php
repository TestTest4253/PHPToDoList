<?php
session_start();
include '../functions.php';
logEvent('User has logged out');
session_unset();
session_destroy();
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

    </style>
    <title>30061640</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="home.php">Home</a>
</nav>
<h1 style="text-align: center">You have been logged out!</h1>
<script>
    setTimeout(function() {
        window.location.href = "home.php";
    }, 1000);
</script>