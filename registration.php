<?php  
require_once "PHP/db_connection.php"; // database connection file imported

function encodeIndexNumber($indexNumber) {
    $base64Encoded = base64_encode($indexNumber);
    $customEncoded = strtr($base64Encoded, [
        '+' => 'U', // Replace '+' with 'U'
        '/' => 'S', // Replace '/' with 'S'
        '=' => 'D'  // Replace '=' with 'D'
    ]);

    $finalEncoded = substr(str_shuffle($customEncoded), 0, 8); 

    return $finalEncoded;
}

if (isset($_POST['register'])) {
    $fullname   = $_POST['fullname'];
    $faculty    = $_POST['faculty'];
    $regNumber  = $_POST['regNumber'];
    $acedemicyr = $_POST['acedemicyr'];
    
    // Check if the student is already registered
    $checkQuery = "SELECT * FROM `student_table` WHERE `UniRegNo` = ?";
    $checkStmt  = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $regNumber);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        setcookie('regNo', $regNumber, time() + (86400 * 30), "/", "", true, true); // Set cookie before any output
        echo "<script>alert('You are already registered ...')</script>";
        echo "<script>window.location.href = 'upload.php';</script>"; // Use JS for redirection
    } else {
        // Generate a unique ID using base64 encoding
        $combineUid = encodeIndexNumber($regNumber);

        // Insert the new registration details into the database
        $query = "INSERT INTO `Student_Table` (`FullName`, `Faculty`, `UniRegNo`, `AcademicYear`, `UniqueID`) VALUES (?, ?, ?, ?, ?)";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("sssss", $fullname, $faculty, $regNumber, $acedemicyr, $combineUid);

        if ($stmt->execute()) {
            // Registration success
            setcookie('regNo', $regNumber, time() + (86400 * 30), "/", "", true, true); // Set cookie before any output
            echo "<script>alert('Registration successful')</script>";
            echo "<script>alert('Congratulations! Your unique ID is: {$combineUid}')</script>";
            echo "<script>window.location.href = 'upload.php';</script>"; // Use JS for redirection
        } else {
            // Registration failed
            echo "<script>alert('Something went wrong!')</script>";
            echo "<script>window.location.href = 'registration.php';</script>"; // Use JS for redirection
        }
        $stmt->close();
    }
    $checkStmt->close();
}

// Handle login verification
if (isset($_POST['login'])) {
    $regNumber = $_POST['regNo'];
    $uniqueId  = $_POST['uniqueId'];

    // Check if the provided credentials match any records in the database
    $loginQuery = "SELECT * FROM `student_table` WHERE `UniRegNo` = ? AND `UniqueID` = ?";
    $loginStmt  = $conn->prepare($loginQuery);
    $loginStmt->bind_param("ss", $regNumber, $uniqueId);
    $loginStmt->execute();
    $loginStmt->store_result();

    if ($loginStmt->num_rows > 0) {
        // Credentials match, login successful
        setcookie('regNo', $regNumber, time() + (86400 * 30), "/", "", true, true); // Set cookie before any output
        echo "<script>alert('Login successful!')</script>";
        echo "<script>window.location.href = 'student_dashboard.php';</script>"; // Redirect to upload page
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid registration details. Please try again.')</script>";
        echo "<script>window.location.href = 'registration.php';</script>"; // Redirect to registration page
    }

    $loginStmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload Resume - Student Career Net</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#">
        <img src="immmage/logo2.jpg" alt="Portal Logo" width="50" />
      </a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="registration.php">Upload Resume</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="company_registration.php">Companies</a>
          </li>
          </li><li class="nav-item">
            <a class="nav-link" href="Admin_Login.php">Admin</a>
            <!-- Added Admin Link -->
          </li>
        </ul>
      </div>
    </nav>

    <!-- Question Board -->
    <div class="container mt-5">
      <h2>Are you already registered?</h2>
      <button id="already-registered" class="btn btn-primary">Yes</button>
      <button id="not-registered" class="btn btn-secondary">No</button>

      <!-- Registration Form (hidden initially) -->
      <div class="registration-form" style="display: none">
        <h3>Register</h3>
        <form id="registration-form" method="POST" action="registration.php">
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input
              type="text"
              class="form-control"
              id="fullName"
              placeholder="Enter your full name"
              name = "fullname"
              required
            />
          </div>
          <div class="form-group">
            <label for="faculty">Faculty</label>
            <input
              type="text"
              class="form-control"
              id="faculty"
              placeholder="Enter your faculty"
              name = "faculty"
              required
            />
          </div>
          <div class="form-group">
            <label for="registrationNumber"
              >University Registration Number</label
            >
            <input
              type="text"
              class="form-control"
              id="registrationNumber"
              placeholder="Enter your registration number"
              name ="regNumber"
              required
            />
          </div>
          <div class="form-group">
            <label for="academicYear">Academic Year</label>
            <input
              type="text"
              class="form-control"
              id="academicYear"
              placeholder="Enter your academic year"
              name="acedemicyr"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary" name="register">Submit</button>
        </form>
      </div>

      <!-- Verification Form (hidden initially) -->
      <div class="verification-form" style="display: none">
        <h3>Verify Your Details</h3>
        <form id="verification-form" method = "POST" action="registration.php" >
          <div class="form-group">
            <label for="verifyID">Registration ID</label>
            <input
              type="text"
              class="form-control"
              id="verifyID"
              name="uniqueId"
              placeholder="Enter your registration ID"
              required
            />
          </div>
          <div class="form-group">
            <label for="verifyRegNumber">University Registration Number</label>
            <input
              type="text"
              class="form-control"
              id="verifyRegNumber"
              name="regNo"
              placeholder="Enter your university registration number"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary" name="login">Verify</button>
        </form>
      </div>

      <!-- Modal for Success/Failure Messages -->
      <div
        class="modal fade"
        id="successModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="successModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="successModalLabel"></h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="redirect-btn">
                OK
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom Script -->
    <script>
      document
        .getElementById("already-registered")
        .addEventListener("click", function () {
          document.querySelector(".registration-form").style.display = "none";
          document.querySelector(".verification-form").style.display = "block";
        });

      document
        .getElementById("not-registered")
        .addEventListener("click", function () {
          document.querySelector(".verification-form").style.display = "none";
          document.querySelector(".registration-form").style.display = "block";
        });
      // Handle Modal OK Button Redirect
      document
        .getElementById("redirect-btn")
        .addEventListener("click", function () {
          $("#successModal").modal("hide");
          // Redirect to the upload page after registration
          window.location.href = "upload.php"; // Change this to your upload page URL
        });
    </script>
  </body>
</html>

