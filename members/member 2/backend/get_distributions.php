<?php

header('Content-Type: application/json');

include 'db.php';

$sql = "SELECT 
            d.id,
            r.resource_name,
            d.quantity,
            d.recipient_name,
            d.distribution_date,
            d.notes
        FROM resource_distributions d
        JOIN resources r ON d.resource_id = r.id
        ORDER BY d.id DESC";

$result = mysqli_query($conn, $sql);

$records = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
}

echo json_encode($records);

mysqli_close($conn);

?>