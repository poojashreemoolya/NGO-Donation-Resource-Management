<?php
header('Content-Type: application/json');

include 'db.php';

$totalResources = 0;
$availableStock = 0;
$distributedResources = 0;
$lowStock = 0;

$result = mysqli_query($conn, "SELECT COUNT(*) AS total, COALESCE(SUM(quantity), 0) AS stock FROM resources");

if ($result) {
    $data = mysqli_fetch_assoc($result);
    $totalResources = $data['total'];
    $availableStock = $data['stock'];
}

$result = mysqli_query($conn, "SELECT COALESCE(SUM(quantity), 0) AS distributed FROM resource_distributions");

if ($result) {
    $data = mysqli_fetch_assoc($result);
    $distributedResources = $data['distributed'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS low FROM resources WHERE quantity <= 5");

if ($result) {
    $data = mysqli_fetch_assoc($result);
    $lowStock = $data['low'];
}

echo json_encode([
    "totalResources" => $totalResources,
    "availableStock" => $availableStock,
    "distributedResources" => $distributedResources,
    "lowStock" => $lowStock
]);

?>