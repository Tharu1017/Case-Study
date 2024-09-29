<?php  
require_once "PHP/db_connection.php"; // Database connection file

// Check if the student is logged in by verifying the cookie
if (!isset($_COOKIE['regNo'])) {
    echo "<script>alert('Please log in first.')</script>";
    echo "<script>window.location.href = 'registration.php';</script>";
    exit();
}

$studentRegNo = $_COOKIE['regNo'];

// Fetch the student details from the student_table
$studentQuery = "SELECT * FROM `student_table` WHERE `UniRegNo` = ?";
$studentStmt = $conn->prepare($studentQuery);
$studentStmt->bind_param("s", $studentRegNo);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();
$student = $studentResult->fetch_assoc();

if (!$student) {
    echo "<script>alert('Student details not found. Please try again.')</script>";
    echo "<script>window.location.href = 'registration.php';</script>";
    exit();
}

// Fetch email and interests from student_job_table
$jobQuery = "SELECT student_job_table.Email, student_job_table.Interests 
             FROM `student_job_table` 
             WHERE `UniRegNo` = ?";
$jobStmt = $conn->prepare($jobQuery);
$jobStmt->bind_param("s", $studentRegNo);
$jobStmt->execute();
$jobResult = $jobStmt->get_result();
$jobDetails = $jobResult->fetch_assoc();

// Handle form submission for editing student details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname      = $_POST['fullname'];
    $faculty       = $_POST['faculty'];
    $academicYear  = $_POST['academic_year'];
    $email         = $_POST['email'];
    $interests     = $_POST['interests'];

    // Update the student details in the database
    $updateQuery = "UPDATE `student_table` SET `FullName` = ?, `Faculty` = ?, `AcademicYear` = ? WHERE `UniRegNo` = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssis", $fullname, $faculty, $academicYear, $studentRegNo);

    if ($updateStmt->execute()) {
        // Update the email and interests in the student_job_table
        $updateJobQuery = "UPDATE `student_job_table` SET `Email` = ?, `Interests` = ? WHERE `UniRegNo` = ?";
        $updateJobStmt = $conn->prepare($updateJobQuery);
        $updateJobStmt->bind_param("sss", $email, $interests, $studentRegNo);
        $updateJobStmt->execute();

        echo "<script>alert('Details updated successfully!')</script>";
        echo "<script>window.location.href = 'student_dashboard.php';</script>"; // Refresh the page after updating
    } else {
        echo "<script>alert('Failed to update details. Please try again.')</script>";
    }

    $updateStmt->close();
    $updateJobStmt->close();
}

$studentStmt->close();
$jobStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($student['FullName']); ?></h2>
        <p>Here are your details. You can update them if needed.</p>

        <form action="" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student['FullName']); ?>" required>
            </div>

            <div class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" id="faculty" name="faculty" value="<?php echo htmlspecialchars($student['Faculty']); ?>" required>
            </div>

            <div class="form-group">
                <label for="academic_year">Academic Year</label>
                <input type="number" class="form-control" id="academic_year" name="academic_year" value="<?php echo htmlspecialchars($student['AcademicYear']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($jobDetails['Email']) ? htmlspecialchars($jobDetails['Email']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="interests">Interests</label>
                <input type="text" class="form-control" id="interests" name="interests" value="<?php echo isset($jobDetails['Interests']) ? htmlspecialchars($jobDetails['Interests']) : ''; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Details</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
