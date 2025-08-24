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

    if (empty($errors)) {
        // Hash the password using MD5
        $hashed_password = md5($password);

        // Store the data in the database
        $conn = new mysqli('localhost', 'root', '', 'ksk');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO login (fullname, email, phone, password, address, city, zip_code) VALUES ('$fullname', '$email', '$phone', '$hashed_password', '$address', '$city', '$zip_code')"; // Updated field name
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Register Page</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container registration-container">
    <div class="register-form">
        <h1>Sign up</h1>
        <form action="" method="post">
            <!-- Full Name Field -->
            <div class="input-group">
                <label for="fullname">Full Name:</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" placeholder="Full Name" required>
                </div>
                <?php if (isset($errors['fullname'])): ?>
                    <span class="error-message"><?php echo $errors['fullname']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="input-group">
                <label for="email">Email:</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required>
                </div>
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Phone Number Field -->
            <div class="input-group">
                <label for="phone">Phone Number:</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Phone Number" required>
                </div>
                <?php if (isset($errors['phone'])): ?>
                    <span class="error-message"><?php echo $errors['phone']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Address Field -->
            <div class="input-group">
                <label for="address">Address:</label>
                <div class="input-wrapper">
                    <i class="fas fa-home"></i>
                    <input type="text" id="address" name="address" value="<?php echo $address; ?>" placeholder="Address" required> <!-- Updated field name -->
                </div>
                <?php if (isset($errors['address'])): ?>
                    <span class="error-message"><?php echo $errors['address']; ?></span> <!-- Updated field name -->
                <?php endif; ?>
            </div>

            <!-- City Field -->
            <div class="input-group">
                <label for="city">City:</label>
                <div class="input-wrapper">
                    <i class="fas fa-city"></i>
                    <input type="text" id="city" name="city" value="<?php echo $city; ?>" placeholder="City" required>
                </div>
                <?php if (isset($errors['city'])): ?>
                    <span class="error-message"><?php echo $errors['city']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Zip Code Field -->
            <div class="input-group">
                <label for="zip_code">Zip Code:</label>
                <div class="input-wrapper">
                    <i class="fas fa-map-pin"></i>
                    <input type="text" id="zip_code" name="zip_code" value="<?php echo $zip_code; ?>" placeholder="Zip Code" required>
                </div>
                <?php if (isset($errors['zip_code'])): ?>
                    <span class="error-message"><?php echo $errors['zip_code']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Password and Re-enter Password Fields -->
            <div class="input-group">
                <label for="password">Password:</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <span class="error-message"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="repassword">Re-enter Password:</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="repassword" name="repassword" placeholder="Re-enter Password" required>
                </div>
                <?php if (isset($errors['repassword'])): ?>
                    <span class="error-message"><?php echo $errors['repassword']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submitRegistration">Register</button>
            <a href="login.php">Already a user? Click here</a>
         </form>
    </div>
</div>

</body>
</html>
