<?php
require_once '../database.php';
require_once '../functions_installateurs.php';
$db = connectDB();
header('Content-Type: application/json');


//GET METHOD
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        echo json_encode(getInstallateurParId($db, $_GET['id']));
        return;
    } else {
        echo json_encode(getInstallateurs($db));
        return;
    }

    //POST METHOD
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    echo json_encode(createInstallateur($db, $data));
    return;

    //PUT METHOD
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
        echo json_encode(updateInstallateur($db, $data));
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID is required for update."]);
        return;
    }

    //DELETE METHOD
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if (isset($_GET['id'])) {
        echo json_encode(deleteInstallateur($db, $_GET['id']));
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID is required for deletion."]);
        return;
    }
}
