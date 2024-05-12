<?php
session_start(); // Start the PHP session

// Initialize error variables
$_SESSION['error_username'] = '';
$_SESSION['error_password'] = '';
$_SESSION['backend_error'] = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username, password, and captcha input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captchaInput = $_POST['captcha-input'];

    // Check if username is empty
    if (empty($username)) {
        $_SESSION['error_username'] = "Username is required";
    }

    // Check if password is empty
    if (empty($password)) {
        $_SESSION['error_password'] = "Password is required";
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
        // Database connection failed
        $_SESSION['backend_error'] = "Database connection failed.";
    } else {
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
                // Check if captcha is correct
                header('Location: final_page.php?username=' . urlencode($username));
                exit();
            } else {
                // Captcha is incorrect
                $_SESSION['backend_error'] = "Password is incorrect.";
                header('Location: log_in.php?username=' . urlencode($username));
                exit();
            }
        } else {
            // Username does not exist
            $_SESSION['backend_error'] = "Username does not exist.";
            header('Location: log_in.php?username=' . urlencode($username));
            exit();
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
