<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MultiFactor Authentication - Sign Up</title>
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

    .signup-form h2 {
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
      margin-right: 20px;
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

    small {
      color: #999;
    }

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

    /* Error message style */
    #captcha-error {
      color: red;
      margin-top: 5px;
    }

    #username-error,
    #password-error {
      color: red;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
   <form class="signup-form" action="#" method="post" onsubmit="return validateForm()">
      <h2>Sign Up</h2>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <div id="username-error"></div>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <div id="password-error"></div>
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
      <div id="captcha-error" style="display: none;">Captcha verification failed. Please try again.</div>

      <button type="submit">Sign Up</button>
      <input type="hidden" id="captcha-text" name="captcha-text">
    </form>
  </div>

  <script>
    document.querySelector(".signup-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  clearErrors();
  const formData = new FormData(e.target);
  try {
    const response = await fetch("sign_up.php", {
      method: "POST",
      body: formData,
    });
    if (!response.ok) {
      throw new Error("Network response was not ok.");
    }
    const result = await response.text();
    if (result === "Success") {
      // Check if captcha is valid before redirecting
      if (validateForm()) {
        // Redirect to the final page only if captcha is valid
        window.location.href = "final_page.php?username=" + encodeURIComponent(formData.get("username"));
      }
    } else {
      if (result.includes("Password")) {
        displayPasswordError(result);
      } else if (result.includes("Username")) {
        displayUsernameError(result);
      } else {
        alert(result); // Display other errors in an alert
      }
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again."); // Display other errors in an alert
  }
});


    function generateCaptcha() {
      const captchaContainer = document.getElementById("captcha-image");
      const captchaInput = document.getElementById("captcha-input");
      const captchaTextContainer = document.getElementById("captcha-text"); // New
      let captchaText = "";
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
        const randomChar = randomchar.charAt(Math.floor(Math.random() * randomchar.length));
        uniquechar += '<span style="color: ' + randomColor + '; font-style: italic; text-decoration: line-through;">' + randomChar + '</span>';
        captchaText += randomChar; // Concatenate the characters
      }
      captchaContainer.innerHTML = uniquechar;
      captchaTextContainer.value = captchaText; // Store the captcha text in the hidden input
    }

    function validateForm() {
      const captchaInput = document.getElementById("captcha-input").value;
      const captchaText = document.getElementById("captcha-text").value; // Get captcha text from hidden input
      const captchaError = document.getElementById("captcha-error");

      if (captchaInput !== captchaText) {
        captchaError.style.display = "block";
        return false; // Prevent form submission
      } else {
        captchaError.style.display = "none";
        return true; // Allow form submission
      }
    }

    function displayUsernameError(message) {
      const usernameError = document.getElementById("username-error");
      usernameError.innerText = message;
      usernameError.style.display = "block";
    }

    function displayPasswordError(message) {
      const passwordError = document.getElementById("password-error");
      passwordError.innerText = message;
      passwordError.style.display = "block";
    }

    function clearErrors() {
      document.getElementById("username-error").style.display = "none";
      document.getElementById("password-error").style.display = "none";
    }

    document.querySelector(".signup-form").addEventListener("submit", async (e) => {
      e.preventDefault();
      clearErrors();
      const formData = new FormData(e.target);
      try {
        const response = await fetch("sign_up.php", {
          method: "POST",
          body: formData,
        });
        if (!response.ok) {
          throw new Error("Network response was not ok.");
        }
        const result = await response.text();
        if (result === "Success") {
          // Redirect or show success message
          window.location.href = "final_page.php?username=" + encodeURIComponent(formData.get("username"));
        } else {
          if (result.includes("Password")) {
            displayPasswordError(result);
          } else if (result.includes("Username")) {
            displayUsernameError(result);
          } else {
            alert(result); // Display other errors in an alert
          }
        }
      } catch (error) {
        console.error("Error:", error);
        alert("An error occurred. Please try again."); // Display other errors in an alert
      }
    });

    generateCaptcha();
  </script>
</body>
</html>
