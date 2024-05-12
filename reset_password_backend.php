<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];
    $captchaInput = $_POST['captcha-input'];

    // Database connection
    $servername = "localhost";
    $username_db = "root"; // Change this to your database username
    $password_db = ""; // Change this to your database password
    $dbname = "db_user"; // Change this to your database name

    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query to check if the username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Username does not exist, set error message in session variable
        $_SESSION['error_message'] = "Username does not exist.";
        // Redirect back to reset password page
        header('Location: forgot_password.php');
        exit();
    }

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        // Passwords do not match, set error message in session variable
        $_SESSION['error_message'] = "Passwords do not match.";
        // Redirect back to reset password page
        header('Location: reset_password.php');
        exit();
    }

    // Generate salt
    $salt = base64_encode(random_bytes(32));

    // Hash the new password with salt
    $hashedPassword = password_hash($newPassword . $salt, PASSWORD_DEFAULT);

    // Prepare and execute query to update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, salt = ? WHERE username = ?");
    $stmt->bind_param("sss", $hashedPassword, $salt, $username);
    $stmt->execute();

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to a success page or wherever you want after password reset
    header('Location: final_page.php');
    exit();
}
?>
