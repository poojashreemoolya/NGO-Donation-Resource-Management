<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST['id'];

    if ($id <= 0) {
        die("Invalid resource ID!");
    }

    // Check whether the resource has distribution records
    $sql = "SELECT COUNT(*) AS total FROM resource_distributions WHERE resource_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($data['total'] > 0) {
        echo "Cannot delete this resource because it has distribution history!";
    } else {

        $sql = "DELETE FROM resources WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Resource deleted successfully!";
        } else {
            echo "Resource not found or could not be deleted!";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);

?>