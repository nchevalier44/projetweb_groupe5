<?php
require_once '../database.php';
require_once '../functions_localisations.php';

$db = connectDB();
header('Content-Type: application/json');

// Handle GET requests for localisations
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Get localisation by ID
    if (isset($_GET['id'])) {
        echo json_encode(getLocalisationParId($db, $_GET['id']));
        return;
    // Get localisation by latitude and longitude
    } else if (isset($_GET['Lat']) && isset($_GET['Lon'])) {
        $lat = $_GET['Lat'];
        $lon = $_GET['Lon'];
        echo json_encode(getLocalisationParLatLon($db, $lat, $lon));
        return;
    // Get all localisations
    } else {
        echo json_encode(getLocalisations($db));
        return;
    }

    // Handle POST requests to create a localisation
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode(createLocalisation($db, $data));
    return;

    // Handle PUT requests to update a localisation
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
        echo json_encode(updateLocalisation($db, $data));
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID is required for update."]);
        return;
    }

    // Handle DELETE requests to remove a localisation
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if (isset($_GET['id'])) {
        echo json_encode(deleteLocalisation($db, $_GET['id']));
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID is required for deletion."]);
        return;
    }
}
