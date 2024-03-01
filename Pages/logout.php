<?php
session_start();
session_destroy();
?>
<p>You have been successfully logged out!</p>
<script>
    setTimeout(function() {
        window.location.href = "home.php";
    }, 1000);
</script>