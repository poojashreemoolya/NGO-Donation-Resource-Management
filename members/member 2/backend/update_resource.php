<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST['id'];
    $name = trim($_POST['resource_name']);
    $category = $_POST['category'];
    $quantity = (int)$_POST['quantity'];
    $unit = trim($_POST['unit']);
    $description = trim($_POST['description']);

    if ($id <= 0 || $name == "" || $quantity < 0 || $unit == "") {
        die("Please enter valid details!");
    }

    $sql = "UPDATE resources
            SET resource_name = ?,
                category = ?,
                quantity = ?,
                unit = ?,
                description = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssissi",
        $name,
        $category,
        $quantity,
        $unit,
        $description,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        echo mysqli_stmt_affected_rows($stmt) >= 0
            ? "Resource updated successfully!"
            : "Update failed!";
    } else {
        echo "Update failed!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>