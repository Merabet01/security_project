<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $security_question = $_POST["security-question"];
    $security_answer = $_POST["security-answer"];
    $captcha_text = $_POST["captcha-text"]; // Retrieve captcha text

    // Check password length and complexity
    if (strlen($password) < 8) {
        echo "Error: Password must be longer.";
        exit;
    }
    // Add more password complexity checks as needed
    
    // Check if captcha is correct
    if ($_POST["captcha-input"] !== $captcha_text) {
        echo "Error: Captcha verification failed.";
        exit;
    }

    // Check for uniqueness of the username
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
    
    // Prepare SQL statement to select username
    $stmt_check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $stmt_check_username->store_result();
    
    // Check if username already exists
    if ($stmt_check_username->num_rows > 0) {
        echo "Error: Username already exists.";
        $stmt_check_username->close(); // Close the prepared statement
        $conn->close(); // Close the database connection
        exit; // Exit the script
    }

    // If username is unique, proceed with inserting new user
    // Generate salt
    $salt = base64_encode(random_bytes(32));
    
    // Hash password with bcrypt
    $hashed_password = password_hash($password . $salt, PASSWORD_DEFAULT);
    
    // Prepare SQL statement to insert user data
    $stmt_insert_user = $conn->prepare("INSERT INTO users (username, password, salt, security_question, security_answer) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert_user->bind_param("sssss", $username, $hashed_password, $salt, $security_question, $security_answer);
    
    // Execute SQL statement to insert new user
    if ($stmt_insert_user->execute() === TRUE) {
        echo "Success"; // Return success message
    } else {
        echo "Error: " . $stmt_insert_user->error;
    }
    
    // Close statements and connection
    $stmt_insert_user->close();
    $conn->close();
} else {
    // Form not submitted
    echo "Error: Form not submitted.";
}

?>
