<?php
session_start();

$username = $_POST['username']; // Assuming you have a hidden input field in your form to pass the username
$securityQuestion = $_POST['security-question'];
$securityAnswer = $_POST['security-answer'];
$captchaInput = $_POST['captcha-input'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your existing code

    

    // Database connection
    $servername = "localhost";
    $username_db = "root"; // Change this to your database username
    $password_db = ""; // Change this to your database password
    $dbname = "db_user"; // Change this to your database name
    
    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Prepare SQL statement to fetch security question and answer for the provided username
    $stmt = $conn->prepare("SELECT security_question, security_answer FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows == 1) {
        // Username exists, fetch the security question and answer
        $row = $result->fetch_assoc();
        $correctSecurityQuestion = $row['security_question'];
        $correctSecurityAnswer = $row['security_answer'];

        // Verify if provided security question and answer match the stored values
        if ($securityQuestion === $correctSecurityQuestion && $securityAnswer === $correctSecurityAnswer) {
            // Security question and answer match, redirect to final_page.php with username parameter
            header('Location: reset_password.php?username=' . urlencode($username));
            exit();
        } else {
            // Security question or answer is incorrect, set error message in session variable
            $_SESSION['error_message'] = "Security question or answer is incorrect.";
        }
    } else {
        // Username does not exist, set error message in session variable
        $_SESSION['error_message'] = "Username does not exist.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to forgot password page
    header('Location: forgot_password.php');
    exit();
}
?>
