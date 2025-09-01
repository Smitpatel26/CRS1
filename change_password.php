<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['customer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = md5($_POST['old_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $passwordCheckQuery = "SELECT password FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($passwordCheckQuery);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $row['password'] === $old_password) {
        if ($new_password === $confirm_password) {
            $new_password_hashed = md5($new_password);
            $updateQuery = "UPDATE customers SET password = ? WHERE customer_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $new_password_hashed, $customer_id);

            if ($stmt->execute()) {
                echo "<p class='message success'>Password changed successfully.</p>";
            } else {
                echo "<p class='message error'>Error updating password: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='message error'>New password and confirm password do not match.</p>";
        }
    } else {
        echo "<p class='message error'>Old password is incorrect.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit_profile.css">
    <title>Change Password</title>
</head>
<body>
    <header>
        <h2>Change Password</h2>
    </header>
    <div class="container">
        <form action="change_password.php" method="POST">
            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" name="old_password" required><br>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required><br>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
