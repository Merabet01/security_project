<?php
session_start();
$_SESSION['captcha_text'] = generateCaptcha(); // Store the generated captcha text in the session

function generateCaptcha() {
    $captchaText = "";
    $randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for ($i = 0; $i < 5; $i++) {
        $randomChar = $randomchar[rand(0, strlen($randomchar) - 1)];
        $captchaText .= $randomChar;
    }

    return $captchaText;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - MultiFactor Authentication</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk"
        crossorigin="anonymous">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .container {
      max-width: 400px;
      margin: 100px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: calc(100% - 20px); /* Adjusted width to accommodate margin */
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: calc(100% - 20px); /* Adjusted width to accommodate margin */
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: red;
      margin-top: 5px;
    }

    /* Captcha styling */
    #captcha-container {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    #captcha-input {
      flex: 1;
      margin-right: 10px;
    }

    #refresh-captcha {
      cursor: pointer;
    }

    #captcha-image {
      width: 100px;
      height: 40px;
      margin-left: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      line-height: 40px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <form id="reset-password-form" action="reset_password_backend.php" method="post" onsubmit="return validateForm()">
      <h2>Reset Password</h2>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new-password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
      </div>
      <!-- Captcha Section -->
      <div id="captcha-container" class="form-group">
        <input type="text" id="captcha-input" name="captcha-input" placeholder="Enter Captcha" required>
        <div id="refresh-captcha" onclick="generateCaptcha()">
          <i class="fas fa-sync"></i>
        </div>
        <div id="captcha-image"><?php echo $_SESSION['captcha_text']; ?></div>
      </div>
      <!-- Error message for captcha verification failure -->
      <div id="captcha-error" class="error-message" style="display: none;">Captcha verification failed. Please try again.</div>
      <button type="submit">Reset Password</button>
    </form>
  </div>

  <script>
  function generateCaptcha() {
    const captchaContainer = document.getElementById("captcha-image");
    let captchaText = "";
    const randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < 5; i++) {
        const randomColor = getRandomColor(); // Get random color for each character
        const randomChar = randomchar.charAt(Math.floor(Math.random() * randomchar.length));
        captchaText += '<span style="color: ' + randomColor + '; font-style: italic; text-decoration: line-through;">' + randomChar + '</span>';
    }
    captchaContainer.innerHTML = captchaText;

    // Set captcha value in session
    sessionStorage.setItem('captcha', captchaText); // Store captcha text in session storage
    $_SESSION['captcha'] = $captchaText;

}

  function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  function validateForm() {
    const captchaInput = document.getElementById("captcha-input").value.toLowerCase();
    const captchaText = document.getElementById("captcha-image").innerText.toLowerCase(); // Use innerText to get the text content without HTML

    if (captchaInput !== captchaText) {
      document.getElementById("captcha-error").style.display = "block";
      return false; // Prevent form submission
    } else {
      document.getElementById("captcha-error").style.display = "none";
      return true; // Allow form submission
    }
  }

  // Generate captcha on page load
  generateCaptcha();
</script>
</body>
</html>
