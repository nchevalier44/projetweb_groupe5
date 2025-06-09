<?php
    require_once '../../database.php';
    require_once '../../functions_panneaux.php';
    $db = connectDB();
    header('Content-Type: application/json');

    // Handle GET requests for panneau modeles
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            // Get modele by ID
            $modele = getModelePanneauParId($db, $_GET['id']);
            if($modele){
                echo json_encode($modele);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
            }
            return;
        }
        else if (isset($_GET['Modele_panneau']) && !empty($_GET['Modele_panneau'])){
            // Get modele ID by name
            $modele = $_GET['Modele_panneau'];
            $idModele = getIdModelePanneauParModele($db, $modele);
            if($idModele){
                echo json_encode($idModele);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
                return;
            }
        } else{
            // Get all modeles
            echo json_encode(getModelesPanneaux($db));
            return;
        }

    // Handle POST requests to create a modele
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Create a new modele if provided
        if(isset($data['modele_panneau']) || isset($_POST['modele_panneau'])){
            $modele = isset($data['modele_panneau']) ? $data['modele_panneau'] : $_POST['modele-panneau'];
            $response = createModelePanneau($db, $modele);
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Modele already exists']);
                return;
            }
        }

        // If required data is missing, return error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle PUT requests to update a modele
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Update modele if ID and new name are provided
        if(isset($_GET['id']) && isset($data['modele-panneau'])){
            $id = $_GET['id'];
            $modele = $data['modele-panneau'];
            $response = updateModelePanneau($db, $id, $modele);
            if($response){
                echo json_encode($response);
                return;
            }
        }
        // If required data is missing, return error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle DELETE requests to remove a modele
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($_GET['id'])){
            // Delete modele by ID
            $id = $_GET['id'];
            if(deleteModelePanneau($db, $id)){
                echo json_encode(['message' => 'Modele successfully deleted']);
                return;
            }
        }
        // If ID is missing or deletion failed, return error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }

