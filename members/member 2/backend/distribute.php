<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resource_id = (int)$_POST['resource_id'];
    $quantity = (int)$_POST['quantity'];
    $recipient_name = trim($_POST['recipient_name']);
    $distribution_date = $_POST['distribution_date'];
    $notes = $_POST['notes'];

    if ($resource_id <= 0 || $quantity <= 0 || $recipient_name == "") {
        die("Please enter valid details.");
    }

    mysqli_begin_transaction($conn);

    try {

        $sql = "SELECT quantity FROM resources WHERE id = ? FOR UPDATE";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $resource_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $resource = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if (!$resource) {
            throw new Exception("Resource not found!");
        }

        if ($quantity > $resource['quantity']) {
            throw new Exception("Insufficient stock available!");
        }

        $sql = "UPDATE resources SET quantity = quantity - ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantity, $resource_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $sql = "INSERT INTO resource_distributions
        (resource_id, quantity, recipient_name, distribution_date, notes)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt, "iisss",
            $resource_id,
            $quantity,
            $recipient_name,
            $distribution_date,
            $notes
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        echo "Resource distributed successfully!";

    } catch (Exception $e) {

        mysqli_rollback($conn);
        echo $e->getMessage();

    }
}

mysqli_close($conn);

?>