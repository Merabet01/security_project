<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - MultiFactor Authentication</title>
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
    input[type="password"],
    select {
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
    <form id="forgot-password-form" action="forgot_password_backend.php" method="post">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="security-question">Security Question:</label>
        <select id="security-question" name="security-question" required>
          <option value="">Select a security question</option>
          <option value="q1">What was the model of your first car?</option>
          <option value="q3">What is the name of your favorite childhood friend?</option>
          <option value="q8">What is the first and last name of your favorite childhood teacher?</option>
          <option value="q4">What is the name of your first pet?</option>
          <option value="q10">What is the name of your favorite sports team?</option>
        </select>
      </div>
      <div class="form-group">
        <label for="security-answer">Security Answer:</label>
        <input type="text" id="security-answer" name="security-answer" required>
      </div>

      <!-- Captcha Section -->
      <div id="captcha-container" class="form-group">
        <input type="text" id="captcha-input" name="captcha-input" placeholder="Enter Captcha" required>
        <div id="refresh-captcha" onclick="generateCaptcha()">
          <i class="fas fa-sync"></i>
        </div>
        <div id="captcha-image"></div>
      </div>
      <!-- Error message for captcha verification failure -->
      <div id="captcha-error" class="error-message" style="display: none;">Captcha verification failed. Please try again.</div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    function generateCaptcha() {
      const captchaContainer = document.getElementById("captcha-image");
      const captchaInput = document.getElementById("captcha-input");
      let uniquechar = "";
      const randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

      function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      }

      for (let i = 0; i < 5; i++) {
        const randomColor = getRandomColor();
        uniquechar += '<span style="color: ' + randomColor + '; font-style: italic; text-decoration: line-through;">' + randomchar.charAt(Math.floor(Math.random() * randomchar.length)) + '</span>';
      }
      captchaContainer.innerHTML = uniquechar;
    }

    function validateForm() {
      const captchaInput = document.getElementById("captcha-input").value;
      const captchaText = document.getElementById("captcha-image").innerText;
      const captchaError = document.getElementById("captcha-error");

      if (captchaInput !== captchaText) {
        captchaError.style.display = "block";
        return false; // Prevent form submission
      } else {
        captchaError.style.display = "none";
        return true; // Allow form submission
      }
    }

    function showSecurityQuestion() {
      const securityQuestionContainer = document.getElementById("security-question");
      securityQuestionContainer.style.display = "block";
    }

    function submitSecurityAnswer() {
      const securityQuestion = document.getElementById("security-question").value;
      const securityAnswer = document.getElementById("security-answer").value;

      // Send the security question and answer to the server for verification
      // You can implement this part using AJAX or form submission
      // Example:
      // const formData = new FormData();
      // formData.append('security-question', securityQuestion);
      // formData.append('security-answer', securityAnswer);
      // fetch('verify-security-answer.php', {
      //   method: 'POST',
      //   body: formData
      // })
      // .then(response => {
      //   if (response.ok) {
      //     // Handle success
      //   } else {
      //     // Handle error
      //   }
      // })
      // .catch(error => console.error('Error:', error));
    }

    // Generate captcha on page load
    generateCaptcha();
  </script>
</body>
</html>
