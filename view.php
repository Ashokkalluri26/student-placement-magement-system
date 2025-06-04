<?php
require_once 'config.php';

// Initialize filter variables
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM students WHERE 1=1";
if ($department_filter) {
    $sql .= " AND department = '" . mysqli_real_escape_string($conn, $department_filter) . "'";
}
if ($status_filter) {
    $sql .= " AND placement_status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}
if ($search) {
    $sql .= " AND (student_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
              OR roll_number LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
              OR company LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
}

$result = mysqli_query($conn, $sql);

// Get unique departments for filter
$dept_query = "SELECT DISTINCT department FROM students ORDER BY department";
$dept_result = mysqli_query($conn, $dept_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Records - Placement Management System</title>
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
                        <a class="nav-link active" href="view.php">View Records</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Filter Records</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" name="department" id="department">
                            <option value="">All Departments</option>
                            <?php while($dept = mysqli_fetch_assoc($dept_result)): ?>
                                <option value="<?php echo htmlspecialchars($dept['department']); ?>"
                                    <?php echo $department_filter == $dept['department'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['department']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Placement Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">All Status</option>
                            <option value="Placed" <?php echo $status_filter == 'Placed' ? 'selected' : ''; ?>>Placed</option>
                            <option value="Not Placed" <?php echo $status_filter == 'Not Placed' ? 'selected' : ''; ?>>Not Placed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" id="search" 
                               value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Search by name, roll number, or company">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Student Records</h4>
                <a href="export.php" class="btn btn-success">Export to Excel</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Roll Number</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>CGPA</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cgpa']); ?></td>
                                        <td><?php echo htmlspecialchars($row['company']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $row['placement_status'] == 'Placed' ? 'success' : 'warning'; ?>">
                                                <?php echo htmlspecialchars($row['placement_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 