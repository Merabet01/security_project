<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $security_question = $_POST["security-question"];
    $security_answer = $_POST["security-answer"];
    
    // Check password length and complexity
    if (strlen($password) < 8) {
        echo " Password must be longer.";
        exit;
    }
    // Add more password complexity checks as needed
    
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
    
    // Check if username is already taken
    $stmt_check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $stmt_check_username->store_result();
    if ($stmt_check_username->num_rows > 0) {
        echo "Error: Username already exists.";
        exit;
    }
    
    // Generate salt
    $salt = base64_encode(random_bytes(32));
    
    // Hash password with bcrypt
    $hashed_password = password_hash($password . $salt, PASSWORD_DEFAULT);
    
    // Prepare SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, password, salt, security_question, security_answer) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $hashed_password, $salt, $security_question, $security_answer);
    
    // Execute SQL statement
    if ($stmt->execute() === TRUE) {
        echo "Success"; // Return success message
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close connection
    $stmt_check_username->close();
    $stmt->close();
    $conn->close();
} else {
    echo "Error: Form not submitted.";
}
?>
