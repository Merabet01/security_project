<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Success</title>
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
      text-align: center;
    }

    h2 {
      color: #007bff;
    }

    p {
      font-size: 18px;
    }

    #username {
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
    <h2>Sign Up Successful</h2>
    <?php if (isset($_GET['username'])) : ?>
      <p>Congratulations, <span id="username"><?php echo htmlspecialchars($_GET['username']); ?></span>, you have successfully signed up!</p>
    <?php else : ?>
      <p>Congratulations, you have successfully signed up!</p>
    <?php endif; ?>
  </div>
</body>
</html>