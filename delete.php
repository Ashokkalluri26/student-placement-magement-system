<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM students WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: view.php");
            exit();
        } else {
            echo "Error deleting record.";
        }
        
        mysqli_stmt_close($stmt);
    }
}

header("location: view.php");
exit();
?> 