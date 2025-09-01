<?php
session_start();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitOtp'])) {
    $enteredOtp = $_POST['otp'];

    // Check if OTP matches and is still valid
    if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry'])) {
        if (time() > $_SESSION['otp_expiry']) {
            $errors['otp'] = "OTP has expired. Please request a new one.";
        } elseif ($enteredOtp == $_SESSION['otp']) {
            // OTP is correct, redirect to password reset page
            header("Location: resetpassword.php");
            exit;
        } else {
            $errors['otp'] = "Invalid OTP. Please try again.";
        }
    } else {
        $errors['otp'] = "No OTP found. Please request a new one.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="verifyotp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="otp-verification-form">
        <h1>Verify OTP</h1>
        <p>Please enter the OTP sent to your registered email.</p>
        
        <form action="" method="post">
            <div class="input-group">
                <label for="otp">OTP:</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                    <?php if (isset($errors['otp'])): ?>
                        <span class="error-message"><?php echo $errors['otp']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" name="submitOtp">Verify OTP</button>

            <p class="resend-link">Didn’t receive OTP? <a href="forgotpassword.php">Resend OTP</a></p>
        </form>
    </div>
</div>

</body>
</html>
