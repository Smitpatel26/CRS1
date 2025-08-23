<?php
session_start();  // Start the session to store user data

$login_error = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $servername = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $dbname = 'car_rental_db';
    $conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

    // Check for connection errors
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the user exists (email or phone number) in the database
    $query = "SELECT * FROM customers WHERE email='$username' OR phone='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Fetch the user data
        $user = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> register</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<div class="register-container">
    <h2>Login</h2>
    <form action="process_login.php" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="submit" value="login">
         <a href="register.php">For Register click here</a>
            <a href="forgotpassword.php">Forget Password</a>

    </form>
</div>
</body>
</html>
