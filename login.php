<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="register-container">
    <h2>Login</h2>
    <form action="process_login.php" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="submit" value="login">
    </form>
</div>

</body>
</html>
