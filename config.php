<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'placement_db');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    mysqli_select_db($conn, DB_NAME);
    
    // Create students table
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        student_name VARCHAR(100) NOT NULL,
        roll_number VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        department VARCHAR(50) NOT NULL,
        cgpa DECIMAL(3,2) NOT NULL,
        company VARCHAR(100),
        package DECIMAL(10,2),
        placement_status ENUM('Placed', 'Not Placed') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error creating table: " . mysqli_error($conn));
    }

    // Check if package column exists, if not add it
    $result = mysqli_query($conn, "SHOW COLUMNS FROM students LIKE 'package'");
    if (mysqli_num_rows($result) == 0) {
        $alter_sql = "ALTER TABLE students ADD COLUMN package DECIMAL(10,2) AFTER company";
        if (!mysqli_query($conn, $alter_sql)) {
            die("Error adding package column: " . mysqli_error($conn));
        }
    }
} else {
    die("Error creating database: " . mysqli_error($conn));
}
?> 