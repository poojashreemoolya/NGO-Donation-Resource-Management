<?php
header('Content-Type: application/json');

include 'db.php';

$sql = "SELECT * FROM resources ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$resources = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $resources[] = $row;
    }
}

echo json_encode($resources);

mysqli_close($conn);
?>