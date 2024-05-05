<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username, password, and captcha input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captchaInput = $_POST['captcha-input'];

    // Check if captcha input matches the stored captcha text in the session
    if ($captchaInput !== $_SESSION['captcha_text']) {
        // Captcha verification failed, set error message in session variable
        $_SESSION['error_message'] = "Captcha verification failed. Please try again.";
        // Redirect back to login page
        header('Location: log_in.php');
        exit();
    }

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

    // Prepare SQL statement to fetch user details including hashed password and salt
    $stmt = $conn->prepare("SELECT password, salt FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows == 1) {
        // Username exists, fetch the hashed password and salt
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $salt = $row['salt'];

        // Verify the password using password_verify function
        if (password_verify($password . $salt, $hashed_password)) {
            // Redirect to final_page.php with username parameter
            header('Location: final_page.php?username=' . urlencode($username));
            exit();
        } else {
            // Password is incorrect, set error message in session variable
            $_SESSION['error_message'] = "Incorrect password.";
        }
    } else {
        // Username does not exist, set error message in session variable
        $_SESSION['error_message'] = "Username does not exist.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to login page
    header('Location: log_in.php');
    exit();
}
?>
