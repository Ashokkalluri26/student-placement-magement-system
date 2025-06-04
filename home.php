<?php
require_once 'config.php';

// Get some statistics for the dashboard
$total_students = 0;
$placed_students = 0;
$avg_package = 0;

$result = mysqli_query($conn, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN placement_status = 'Placed' THEN 1 ELSE 0 END) as placed,
    COALESCE(AVG(NULLIF(package, 0)), 0) as avg_package
FROM students");

if ($row = mysqli_fetch_assoc($result)) {
    $total_students = $row['total'];
    $placed_students = $row['placed'];
    $avg_package = number_format($row['avg_package'], 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Placement Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="style.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
            opacity: 0.1;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .stats-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background: white;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #1e3c72;
        }

        .animated-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e3c72;
            animation: countUp 2s ease-out;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .study-gif {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 20px 0;
            transition: transform 0.3s ease;
        }

        .study-gif:hover {
            transform: scale(1.05);
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
            color: #1e3c72;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #1e3c72;
            animation: expandWidth 2s ease-out;
        }

        @keyframes expandWidth {
            from { width: 0; }
            to { width: 60px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">Student Placement Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view.php">View Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="import.php">Import from CSV</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-4">Student Placement Management System</h1>
                    <p class="lead mb-4">Track, manage, and analyze student placement data with our comprehensive management system.</p>
                    <a href="index.php" class="btn btn-light btn-lg">Get Started</a>
                </div>
                <div class="col-md-6">
                    <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JrY3BxOGhtNXh4NmRwOWx6Ymdvd3g2amdwbG5wOWZpYjJwbiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/L1R1tvI9svkIWwpVYr/giphy.gif" alt="Education" class="img-fluid study-gif">
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="stats-card card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-people feature-icon"></i>
                        <h3 class="animated-number"><?php echo $total_students; ?></h3>
                        <p class="text-muted">Total Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-briefcase feature-icon"></i>
                        <h3 class="animated-number"><?php echo $placed_students; ?></h3>
                        <p class="text-muted">Placed Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-graph-up feature-icon"></i>
                        <h3 class="animated-number"><?php echo $avg_package; ?></h3>
                        <p class="text-muted">Average Package (LPA)</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="section-title mt-5">Key Features</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card card p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-person-plus feature-icon"></i>
                        <h4>Student Management</h4>
                        <p>Add and manage student profiles with comprehensive details including academic performance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card card p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-spreadsheet feature-icon"></i>
                        <h4>Bulk Import</h4>
                        <p>Import multiple student records at once using CSV files for efficient data management.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card card p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-search feature-icon"></i>
                        <h4>Easy Access</h4>
                        <p>Quick and easy access to student placement records with powerful search capabilities.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <h2 class="section-title">About the Project</h2>
                <p>The Student Placement Management System is designed to streamline the process of managing student placement data. It provides a comprehensive solution for educational institutions to track their students' placement journey.</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Track placement statistics</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Monitor student performance</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Generate reports</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Manage company information</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcmN2ZWV1Y2pwNzVvNmgydWsyeGV4NWd1bXE1aHN0ZHB0ZWx0Z2lqaCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/qgQUggAC3Pfv687qPC/giphy.gif" alt="Study" class="img-fluid study-gif">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 