<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Career Net</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#">
        <img src="immmage/logo2.jpg" alt="Portal Logo" width="50" />
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
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
          </li><li class="nav-item">
            <a class="nav-link" href="Admin_Login.php">Admin</a>
            <!-- Added Admin Link -->
          </li>

        </ul>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
      <div class="container text-center">
        <h1>Welcome to the Student Career Net</h1>
        <p class="lead">Connecting Students with Local Companies</p>
        <a href="registration.php" class="btn btn-primary btn-lg upload-resume"
          >Upload Your Resume</a
        >
        <a
          href="company_registration.php"
          class="btn btn-outline-light btn-lg view-resume"
          >View Resumes</a
        >
      </div>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
      <h2>About the Portal</h2>
      <p>
        This portal is designed to help university students easily upload their
        resumes and connect with local companies looking for fresh talent. The
        system simplifies job searching by providing a platform where students
        can showcase their skills and employers can find suitable candidates.
      </p>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <p>&copy; 2024 Student Career Net. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
