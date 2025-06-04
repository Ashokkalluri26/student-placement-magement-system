<?php
require_once 'config.php';

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"];
    $file_ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    // Validate file extension
    if ($file_ext != "csv") {
        $error_message = "Please upload only CSV files.";
    } else {
        try {
            $handle = fopen($file["tmp_name"], "r");
            
            // Skip header row
            fgetcsv($handle);
            
            $success_count = 0;
            $error_count = 0;
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) >= 9) { // Make sure we have all required fields
                    $student_name = mysqli_real_escape_string($conn, $row[0]);
                    $roll_number = mysqli_real_escape_string($conn, $row[1]);
                    $email = mysqli_real_escape_string($conn, $row[2]);
                    $phone = mysqli_real_escape_string($conn, $row[3]);
                    $department = mysqli_real_escape_string($conn, $row[4]);
                    $cgpa = mysqli_real_escape_string($conn, $row[5]);
                    $company = mysqli_real_escape_string($conn, $row[6]);
                    $package = mysqli_real_escape_string($conn, $row[7]);
                    $placement_status = mysqli_real_escape_string($conn, $row[8]);
                    
                    $sql = "INSERT INTO students (student_name, roll_number, email, phone, department, cgpa, company, package, placement_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "sssssdsds", $student_name, $roll_number, $email, $phone, $department, $cgpa, $company, $package, $placement_status);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
            }
            
            fclose($handle);
            
            if ($success_count > 0) {
                $success_message = "Successfully imported $success_count records.";
                if ($error_count > 0) {
                    $success_message .= " ($error_count records failed)";
                }
            } else {
                $error_message = "No records were imported successfully.";
            }
            
        } catch (Exception $e) {
            $error_message = "Error reading CSV file: " . $e->getMessage();
        }
    }
}

// Create a sample CSV file for download
if (isset($_POST['download_sample'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sample_students.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for proper Excel display
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header row
    fputcsv($output, ['Student Name', 'Roll Number', 'Email', 'Phone', 'Department', 'CGPA', 'Company', 'Package', 'Placement Status']);
    
    // Sample data rows
    fputcsv($output, ['John Doe', 'CS001', 'john@example.com', '1234567890', 'Computer Science', '8.50', 'Tech Corp', '12.5', 'Placed']);
    fputcsv($output, ['Jane Smith', 'DS001', 'jane@example.com', '9876543210', 'Data Science', '9.20', 'AI Solutions', '15.5', 'Placed']);
    
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Student Records - Student Placement Management System</title>
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
                    <li class="nav-item">
                        <a class="nav-link active" href="import.php">Import from CSV</a>
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
                <h4>Import Student Records from CSV</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5>Instructions:</h5>
                    <ol>
                        <li>Download the sample CSV file using the button below</li>
                        <li>Open it in Excel or any spreadsheet software</li>
                        <li>Add your student records following the same format</li>
                        <li>Save as CSV (Comma delimited)</li>
                        <li>Upload the file using the form below</li>
                    </ol>
                </div>

                <form method="POST" action="" class="mb-4">
                    <button type="submit" name="download_sample" class="btn btn-secondary">
                        <i class="bi bi-download"></i> Download Sample CSV
                    </button>
                </form>

                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Please ensure your CSV file has the following columns in order:<br>
                            Student Name, Roll Number, Email, Phone, Department, CGPA, Company, Package, Placement Status
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Records</button>
                </form>

                <div class="mt-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Tips for CSV Import:</h5>
                        <ul class="mb-0">
                            <li>Save your Excel file as "CSV (Comma delimited)"</li>
                            <li>Make sure all required fields are filled</li>
                            <li>For placement status, use either "Placed" or "Not Placed"</li>
                            <li>CGPA should be between 0 and 10</li>
                            <li>Package should be in LPA (Lakhs Per Annum)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 