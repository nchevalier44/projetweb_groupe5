<?php
require_once '../database.php';
require_once '../functions_villes.php';

$db = connectDB();
header('Content-Type: application/json');

// Handle GET requests for villes
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Get ville by ID
    if (isset($_GET['id'])) {
        echo json_encode(getVilleParId($db, $_GET['id']));
        return;
    // Get ville by standard name
    } else if (isset($_GET['Nom_standard'])){
        echo json_encode(getVilleParNom($db,$_GET['Nom_standard']));
        return;
    // Get all villes
    } else {
        echo json_encode(getVilles($db));
        return;
    }
}