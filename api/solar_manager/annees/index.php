<?php
require_once '../database.php';
require_once '../functions_annees.php';

$db = connectDB();
header('Content-Type: application/json');

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Return all 'annees' if 'id' is not set
    if (!isset($_GET['id'])) {
        echo json_encode(getAnnees($db));
        return;
    } 
}