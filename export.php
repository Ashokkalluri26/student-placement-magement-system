<?php
require_once 'config.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="student_records.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Get all student records
$sql = "SELECT * FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// Create Excel header row
echo "ID\tStudent Name\tRoll Number\tEmail\tPhone\tDepartment\tCGPA\tCompany\tPlacement Status\tDate Added\n";

// Output data rows
while($row = mysqli_fetch_assoc($result)) {
    echo $row['id'] . "\t";
    echo $row['student_name'] . "\t";
    echo $row['roll_number'] . "\t";
    echo $row['email'] . "\t";
    echo $row['phone'] . "\t";
    echo $row['department'] . "\t";
    echo $row['cgpa'] . "\t";
    echo $row['company'] . "\t";
    echo $row['placement_status'] . "\t";
    echo $row['created_at'] . "\n";
}

exit();
?> 