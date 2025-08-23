<?php
session_start();  // Start the session to store user data

$errors = [];
$fullname = $email = $phone = $password = $repassword = $address = $city = $zip_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRegistration'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $address = $_POST['address']; // Updated field name
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    
    // Validate full name
    if (empty($fullname)) {
        $errors['fullname'] = "Full name is required.";
    }
    
    // Validate phone
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
        $errors['phone'] = "Please enter a valid phone number.";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}$/", $password)) {
        $errors['password'] = "Please enter a strong password.";
    }
    
    // Match passwords
    if ($password !== $repassword) {
        $errors['repassword'] = "Passwords do not match.";
    }

    // Validate address
    if (empty($address)) { // Updated field name
        $errors['address'] = "Address is required."; // Updated field name
    }

    // Validate city
    if (empty($city)) {
        $errors['city'] = "City is required.";
    }

    // Validate zip code
    if (empty($zip_code)) {
        $errors['zip_code'] = "Zip code is required.";
    }

        $sql = "INSERT INTO customers (fullname, email, phone, password, address, city, zip_code) VALUES ('$fullname', '$email', '$phone', '$hashed_password', '$address', '$city', '$zip_code')"; // Updated field name
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="register-container">
    <form method="POST">
        <h2>Register</h2>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <input type="text" name="fullname" placeholder="Enter fullname" required>
        <input type="email" name="email" placeholder="Enter email" required> <!-- Added email field -->
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="address" placeholder="Address" required> <!-- Updated field name -->
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="zipcode" placeholder="zipcode" required>
         <input type="password" name="password" placeholder="Enter Password" required>
        <input type="password" name="repassword" placeholder="rePassword" required>
        <input type="submit" value="register">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
    </div>
</body>
</html>

