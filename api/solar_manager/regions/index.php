<?php
require_once '../database.php';
require_once '../functions_regions.php';

$db = connectDB();
header('Content-Type: application/json');

// Handle GET requests for regions
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Get region by ID
    if (isset($_GET['id'])) {
        echo json_encode(getRegionParId($db, $_GET['id']));
        return;
    // Get all regions
    } else {
        echo json_encode(getRegions($db));
        return;
    }
}