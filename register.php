<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Here, insert into database (this is just a message for demo)
    $success = "Account created for $username!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
    <form method="POST">
        <h2>Register</h2>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="pincode" placeholder="Pincode" required>
        <input type="submit" value="register">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
    </div>
</body>
</html>
