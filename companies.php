<?php
require_once "PHP/db_connection.php"; // Database connection

// Check if the company is logged in by verifying the cookie
if (!isset($_COOKIE['combineUid'])) {
    echo "<script>alert('Please log in first.')</script>";
    echo "<script>window.location.href = 'company_registration.php';</script>";
    exit();
}

$combineUid = $_COOKIE['combineUid'];

// Fetch company details
$companyQuery = "SELECT * FROM `company_table` WHERE `UniqueWebsiteID` = ?";
$companyStmt = $conn->prepare($companyQuery);
$companyStmt->bind_param("s", $combineUid);
$companyStmt->execute();
$companyResult = $companyStmt->get_result();
$company = $companyResult->fetch_assoc();

// Fetch associated student details
$studentQuery = "SELECT * FROM `student_table` JOIN  `student_job_table` ON student_table.UniRegNo=student_job_table.UniRegNo";
$studentStmt = $conn->prepare($studentQuery);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();

// Handle email sending

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust these paths to match where you've extracted PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

////////////////////////// Handle email sending


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendEmail'])) {
    // Validate email
    $studentEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); 
    // Sanitize company name
    $companyName = htmlspecialchars($company['CompanyName'], ENT_QUOTES, 'UTF-8'); 
    $companyEmail = "pankajamalshan1001@example.com"; // Replace with the actual company email

    if ($studentEmail) { // Proceed only if the email is valid
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 0;                       // Disable verbose debug output for production
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';       // Specify Gmail SMTP server
            $mail->SMTPAuth   = true;                   // Enable SMTP authentication
            $mail->Username   = 'student.career.eusl@gmail.com'; // SMTP username
            $mail->Password   = 'ksvf acrx hmpp xker';  // SMTP password (App Password)
            $mail->SMTPSecure = 'tls';                  // Enable TLS encryption
            $mail->Port       = 587;                    // TCP port to connect to

            // Recipients
            $mail->setFrom('student.career.eusl@gmail.com', $companyName);
            $mail->addAddress($studentEmail);  // Add recipient's email

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'Message from ' . $companyName;
            $mail->Body    = 'Dear student, we would like to discuss opportunities with you.';
            $mail->AltBody = 'Dear student, we would like to discuss opportunities with you.'; // Plain text body

            // Send the email
            $mail->send();
            echo "<script>alert('Email sent successfully!');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send email. Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Invalid email address.');</script>";
    }
}

/////////////////

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details - Student Career Net</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Student Career Net</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="company_registration.php">Companies</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Company Details Section -->
        <h2 class="text-center">Company Details</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php echo htmlspecialchars($company['CompanyName']); ?></h4>
                <p class="card-text">
                    <strong>Address:</strong> <?php echo htmlspecialchars($company['Address']); ?><br>
                    <strong>Contact Number:</strong> <?php echo htmlspecialchars($company['ContactNo']); ?><br>
                    <strong>Unique ID:</strong> <?php echo htmlspecialchars($company['UniqueWebsiteID']); ?>
                </p>
            </div>
        </div>

        <!-- Student Details Section -->
        <h3 class="text-center">Associated Students</h3>
        <?php if ($studentResult->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Student Name</th>
                        <th>Faculty</th>
                        <th>University Registration Number</th>
                        <th>Email</th>
                        <th>Interests</th>
                        <th>Action</th> <!-- New column for sending email -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $studentResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                            <td><?php echo htmlspecialchars($student['Faculty']); ?></td>
                            <td><?php echo htmlspecialchars($student['UniRegNo']); ?></td>
                            <td><?php echo htmlspecialchars($student['Email']); ?></td>
                            <td><?php echo htmlspecialchars($student['Interests']); ?></td>
                            <td>
                                <!-- Send Email Form -->
                                <form method="POST">
                                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($student['Email']); ?>">
                                    <button type="submit" name="sendEmail" class="btn btn-primary">Send Email</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No students associated with this company yet.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer bg-light text-center mt-4">
        <p>&copy; 2024 Student Career Net. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
