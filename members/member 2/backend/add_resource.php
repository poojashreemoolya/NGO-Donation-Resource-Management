<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['resource_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $description = $_POST['description'];

    $sql = "INSERT INTO resources 
    (resource_name, category, quantity, unit, description)
    VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt, "ssiss",
        $name, $category, $quantity, $unit, $description
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "Resource added successfully!";
    } else {
        echo "Error adding resource!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>