<?php
require_once 'config.php';

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $roll_number = mysqli_real_escape_string($conn, $_POST['roll_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $cgpa = mysqli_real_escape_string($conn, $_POST['cgpa']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);
    $placement_status = mysqli_real_escape_string($conn, $_POST['placement_status']);

    $sql = "INSERT INTO students (student_name, roll_number, email, phone, department, cgpa, company, package, placement_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssdsds", $student_name, $roll_number, $email, $phone, $department, $cgpa, $company, $package, $placement_status);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Student record added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Placement Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
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
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Add Student</a>
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

    <div class="container mt-4">
        <?php if($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Add Student Placement Record</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_name" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="student_name" name="student_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roll_number" class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="roll_number" name="roll_number" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Mechanical">Mechanical</option>
                                <option value="Civil">Civil</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Cyber Security">Cyber Security</option>
                                <option value="AI & ML">AI & ML</option>
                                <option value="IoT">IoT</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cgpa" class="form-label">CGPA</label>
                            <input type="number" step="0.01" min="0" max="10" class="form-control" id="cgpa" name="cgpa" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="company" class="form-label">Company Placed In</label>
                            <input type="text" class="form-control" id="company" name="company">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="package" class="form-label">Package (LPA)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="package" name="package">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="placement_status" class="form-label">Placement Status</label>
                            <select class="form-select" id="placement_status" name="placement_status" required>
                                <option value="">Select Status</option>
                                <option value="Placed">Placed</option>
                                <option value="Not Placed">Not Placed</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html> 