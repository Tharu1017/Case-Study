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
                    <a class="nav-link" href="index.html">Home</a>
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
