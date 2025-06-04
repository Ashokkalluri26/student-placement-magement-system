<?php
require_once 'config.php';

$success_message = $error_message = "";
$student = null;

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM students WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                $student = mysqli_fetch_assoc($result);
            } else {
                header("location: view.php");
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $roll_number = mysqli_real_escape_string($conn, $_POST['roll_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $cgpa = mysqli_real_escape_string($conn, $_POST['cgpa']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $placement_status = mysqli_real_escape_string($conn, $_POST['placement_status']);

    $sql = "UPDATE students SET 
            student_name=?, roll_number=?, email=?, phone=?, 
            department=?, cgpa=?, company=?, placement_status=? 
            WHERE id=?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssdssi", 
            $student_name, $roll_number, $email, $phone, 
            $department, $cgpa, $company, $placement_status, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Record updated successfully!";
            // Refresh student data
            $student = array(
                'id' => $id,
                'student_name' => $student_name,
                'roll_number' => $roll_number,
                'email' => $email,
                'phone' => $phone,
                'department' => $department,
                'cgpa' => $cgpa,
                'company' => $company,
                'placement_status' => $placement_status
            );
        } else {
            $error_message = "Error updating record: " . mysqli_error($conn);
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
    <title>Edit Student Record - Placement Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Student Placement Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view.php">View Records</a>
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

        <?php if($student): ?>
            <div class="card">
                <div class="card-header">
                    <h4>Edit Student Record</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation" novalidate>
                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" 
                                       value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="roll_number" class="form-label">Roll Number</label>
                                <input type="text" class="form-control" id="roll_number" name="roll_number" 
                                       value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($student['email']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Computer Science" <?php echo $student['department'] == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                    <option value="Information Technology" <?php echo $student['department'] == 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                    <option value="Electronics" <?php echo $student['department'] == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                                    <option value="Mechanical" <?php echo $student['department'] == 'Mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                                    <option value="Civil" <?php echo $student['department'] == 'Civil' ? 'selected' : ''; ?>>Civil</option>
                                    <option value="Data Science" <?php echo $student['department'] == 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                    <option value="Cyber Security" <?php echo $student['department'] == 'Cyber Security' ? 'selected' : ''; ?>>Cyber Security</option>
                                    <option value="AI & ML" <?php echo $student['department'] == 'AI & ML' ? 'selected' : ''; ?>>AI & ML</option>
                                    <option value="IoT" <?php echo $student['department'] == 'IoT' ? 'selected' : ''; ?>>IoT</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cgpa" class="form-label">CGPA</label>
                                <input type="number" step="0.01" min="0" max="10" class="form-control" id="cgpa" name="cgpa" 
                                       value="<?php echo htmlspecialchars($student['cgpa']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Company Placed In</label>
                                <input type="text" class="form-control" id="company" name="company" 
                                       value="<?php echo htmlspecialchars($student['company']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="placement_status" class="form-label">Placement Status</label>
                                <select class="form-select" id="placement_status" name="placement_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Placed" <?php echo $student['placement_status'] == 'Placed' ? 'selected' : ''; ?>>Placed</option>
                                    <option value="Not Placed" <?php echo $student['placement_status'] == 'Not Placed' ? 'selected' : ''; ?>>Not Placed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Record</button>
                                <a href="view.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Student record not found.</div>
        <?php endif; ?>
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