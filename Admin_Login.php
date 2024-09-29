<?php
require_once "PHP/db_connection.php"; // database connection file imported
if (isset($_POST['login'])) {
  $uname = $_POST['uname'];
  $pw  = $_POST['pw'];

  // Check if the provided credentials match any records in the database
  $loginQuery = "SELECT * FROM `Admin` WHERE `Email` = ? AND `Password` = ?";
  $loginStmt  = $conn->prepare($loginQuery);
  $loginStmt->bind_param("ss", $uname, $pw);
  $loginStmt->execute();
  $loginStmt->store_result();

  if ($loginStmt->num_rows > 0) {
      // Credentials match, login successful
      setcookie('email', $uname, time() + (86400 * 30), "/", "", true, true); // Set cookie before any output
      echo "<script>alert('Login successful!')</script>";
      echo "<script>window.location.href = 'admin.php';</script>"; // Redirect to upload page
  } else {
      // Invalid credentials
      echo "<script>alert('Invalid registration details. Please try again.')</script>";
      echo "<script>window.location.href = 'admin_login.php';</script>"; // Redirect to registration page
  }

  $loginStmt->close();
}


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Roboto", sans-serif;
        background-color: #f4f4f9;
      }

      .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .login-box {
        background: #fff;
        padding: 40px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
      }

      .login-box h2 {
        text-align: center;
        margin-bottom: 30px;
      }

      .btn-primary {
        width: 100%;
      }

      .error-msg {
        color: red;
        display: none;
        text-align: center;
        margin-top: 10px;
      }
      /* Back to Home button in the upper-left corner */
      .back-to-home {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        background-color: #6c757d;
        color: white;
        border-radius: 5px;
        text-decoration: none;
      }
      .back-to-home:hover {
        background-color: #5a6268;
        text-decoration: none;
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="login-box">
        <!-- Back to Home Button -->
        <a href="index.php" class="back-to-home">Back to Home</a>
        <h2>Admin Login</h2>
        <form id="adminLoginForm" action="Admin_Login.php" method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="uname" id="username" required />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              name="pw"
              class="form-control"
              id="password"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary" name="login">Login</button>
          <!-- <p class="error-msg" id="errorMsg" hidden>Invalid Username or Password</p> -->
        </form>
      </div>
    </div>

 
  </body>
</html>
