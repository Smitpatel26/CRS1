<?php
session_start();

$login_error = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ksk');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Find user by email OR phone
    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ? OR phone = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ Case 1: Password is hashed
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['username'] = $user['email'];
            header("Location: home.php");
            exit();
        } 
        // ✅ Case 2: Password was stored in plain text (old accounts)
        elseif ($password === $user['password']) {
            // Re-hash it and update DB for future logins
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE login SET password=? WHERE customer_id=?");
            $update->bind_param("si", $newHash, $user['customer_id']);
            $update->execute();

            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['username'] = $user['email'];
            header("Location: home.php");
            exit();
        } 
        else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Login Page</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="login-form">
        <h1>Sign in</h1>
        <form action="" method="post">
            <div class="input-group">
                <label for="username">Username (Email or Phone):</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($username); ?>" 
                           placeholder="Enter Email or Phone" required>
                </div>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <button type="submit">Login</button>
            <?php if ($login_error): ?>
                <span class="error-message" style="color:red;"><?php echo $login_error; ?></span>
            <?php endif; ?>
            <p><a href="register.php">For Register click here</a></p>
            <p><a href="forgotpassword.php">Forget Password</a></p>
        </form>
    </div>
</div>

</body>
</html>
