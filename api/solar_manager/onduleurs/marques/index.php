<?php
require_once '../../database.php';
require_once '../../functions_onduleurs.php';
$db = connectDB();
header('Content-Type: application/json');

// Handle GET requests for onduleur marques
if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            $marque = getMarqueOnduleurParId($db, $_GET['id']);
            if($marque){
                echo json_encode($marque);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Marque not found']);
            }
            return;
        } else if (isset($_GET['Marque_onduleur']) && !empty($_GET['Marque_onduleur'])){
            // Get marque ID by name
            $marque = $_GET['Marque_onduleur'];
            $idMarque = getIdMarqueOnduleurParMarque($db, $marque);
            if($idMarque){
                echo json_encode($idMarque);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Marque not found']);
                return;
            }
        } else{
            // Get all marques
            echo json_encode(getMarquesOnduleurs($db));
            return;
        }

    // Handle POST requests to create a marque
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Create a new marque if provided
        if(isset($data['marque_onduleur']) || isset($_POST['marque_onduleur'])){
            $marque = isset($data['marque_onduleur']) ? $data['marque_onduleur'] : $_POST['marque_onduleur'];
            $response = createMarqueOnduleur($db, $marque);
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Marque already exists']);
                return;
            }
        }

        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle PUT requests to update a marque
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Update marque if ID and new name are provided
        if(isset($_GET['id']) && isset($data['marque-onduleur'])){
            $id = $_GET['id'];
            $marque = $data['marque-onduleur'];
            $response = updateMarqueOnduleur($db, $id, $marque);
            if($response){
                echo json_encode($response);
                return;
            }
        }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle DELETE requests to remove a marque
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            if(deleteMarqueOnduleur($db, $id)){
                echo json_encode(['message' => 'Marque successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
}