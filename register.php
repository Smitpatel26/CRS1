<?php
session_start();

$errors = [];
$fullname = $email = $phone = $password = $repassword = $address = $city = $zip_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRegistration'])) {
    $fullname   = trim($_POST['fullname']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $password   = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);
    $address    = trim($_POST['address']);
    $city       = trim($_POST['city']);
    $zip_code   = trim($_POST['zip_code']);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ksk');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate empty fields
    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($repassword) || empty($address) || empty($city) || empty($zip_code)) {
        $errors[] = "All fields are required.";
    }

    // Validate password match
    if ($password !== $repassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email or phone already exists
    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email or Phone already exists. Please use another.";
    }

    // If no errors → insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO login (fullname, email, phone, password, address, city, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullname, $email, $phone, $hashed_password, $address, $city, $zip_code);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
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
    <title>Car Rental Register Page</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .password-strength {
            font-size: 14px;
            margin-top: 5px;
            font-weight: bold;
        }
        .weak {
            color: red;
        }
        .medium {
            color: orange;
        }
        .strong {
            color: green;
        }

        .error-msg {
         color: red;
        font-size: 13px;
        margin-top: 4px;
        display: none; /* hidden by default */
}
</style>

    </style>
</head>
<body>

<div class="container registration-container">

<body>

<div class="container registration-container">
    
    <!-- Intro Section -->
    <div class="intro-section" style="text-align:center; margin-bottom: 20px;">
        <h1 style="color:#2c3e50; font-size:28px;">🚘 Welcome to CarGo-Car Rental System</h1>
        <p style="font-size:16px; color:#555;">
            Book cars easily and quickly with our hassle-free online car rental service.
        </p>
    </div>


    <div class="register-form">
        <h1>Sign up</h1>
<form action="" method="post" id="registerForm" onsubmit="return validateForm()">
    <!-- Full Name -->
    <div class="input-group">
        <label for="fullname">Full Name:</label>
        <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" 
                   value="<?php echo $fullname; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- Email -->
    <div class="input-group">
        <label for="email">Email:</label>
        <div class="input-wrapper">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Email" 
                   value="<?php echo $email; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- Phone -->
    <div class="input-group">
        <label for="phone">Phone Number:</label>
        <div class="input-wrapper">
            <i class="fas fa-phone"></i>
            <input type="tel" id="phone" name="phone" placeholder="Phone Number"
                   value="<?php echo $phone; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- Address -->
    <div class="input-group">
        <label for="address">Address:</label>
        <div class="input-wrapper">
            <i class="fas fa-home"></i>
            <input type="text" id="address" name="address" placeholder="Address"
                   value="<?php echo $address; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- City -->
    <div class="input-group">
        <label for="city">City:</label>
        <div class="input-wrapper">
            <i class="fas fa-city"></i>
            <input type="text" id="city" name="city" placeholder="City"
                   value="<?php echo $city; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- Zip Code -->
    <div class="input-group">
        <label for="zip_code">Zip Code:</label>
        <div class="input-wrapper">
            <i class="fas fa-map-pin"></i>
            <input type="text" id="zip_code" name="zip_code" placeholder="Zip Code"
                   value="<?php echo $zip_code; ?>" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <!-- Password -->
    <div class="input-group">
        <label for="password">Password:</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" 
                   required onkeyup="checkPasswordStrength()">
        </div>
        <div id="password-strength-status" class="password-strength"></div>
        <small class="error-msg"></small>
    </div>

    <!-- Confirm Password -->
    <div class="input-group">
        <label for="repassword">Re-enter Password:</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="repassword" name="repassword" placeholder="Re-enter Password" required>
        </div>
        <small class="error-msg"></small>
    </div>

    <button type="submit" name="submitRegistration">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
</form>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let inputs = document.querySelectorAll("#registerForm input[required]");

    // Run validation on blur (when leaving a field)
    inputs.forEach(input => {
        input.addEventListener("blur", function () {
            validateField(input);
        });

        // Also remove error once user starts typing
        input.addEventListener("input", function () {
            validateField(input);
        });
    });
});

function validateField(input) {
    let errorMsg = input.parentElement.parentElement.querySelector(".error-msg");

    if (input.value.trim() === "") {
        errorMsg.innerText = input.getAttribute("placeholder") + " is required!";
        errorMsg.style.display = "block";
        return false;
    } else {
        errorMsg.innerText = "";
        errorMsg.style.display = "none";
        return true;
    }
}

function validateForm() {
    let isValid = true;
    let inputs = document.querySelectorAll("#registerForm input[required]");

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    return isValid; // form submits only if all fields are valid
}

function checkPasswordStrength() {
    let password = document.getElementById("password").value;
    let strengthStatus = document.getElementById("password-strength-status");

    if (password.length === 0) {
        strengthStatus.innerHTML = "";
        return;
    }

    let mediumRegex = /^(?=.*[a-z])(?=.*[0-9]).{5,10}$/;
    let strongRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}$/;

    if (strongRegex.test(password)) {
        strengthStatus.className = "password-strength strong";
        strengthStatus.innerHTML = "Password is Strong";
    } else if (mediumRegex.test(password)) {
        strengthStatus.className = "password-strength medium";
        strengthStatus.innerHTML = "Password is Medium";
    } else {
        strengthStatus.className = "password-strength weak";
        strengthStatus.innerHTML = "Password is Weak";
    }
}
</script>

</body>
</html>
