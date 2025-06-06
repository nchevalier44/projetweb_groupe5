<?php
require_once '../database.php';
require_once '../functions_regions.php';
$db = connectDB();
header('Content-Type: application/json');


//GET METHOD
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        echo json_encode(getRegionParId($db, $_GET['id']));
        return;
    } else {
        echo json_encode(getRegions($db));
        return;
    }
}