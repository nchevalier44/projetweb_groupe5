<?php
    require_once '../../database.php';
    require_once '../../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');

    // Handle GET requests for onduleur modeles
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        // Get modele by ID
        if(isset($_GET['id'])){
            $modele = getModeleOnduleurParId($db, $_GET['id']);
            if($modele){
                echo json_encode($modele);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
            }
            return;
        // Get modele ID by name
        } else if (isset($_GET['Modele_onduleur']) && !empty($_GET['Modele_onduleur'])){
            $modele = $_GET['Modele_onduleur'];
            $idModele = getIdModeleOnduleurParModele($db, $modele);
            if($idModele){
                echo json_encode($idModele);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
                return;
            }
        // Get all modeles
        } else{
            echo json_encode(getModelesOnduleurs($db));
            return;
        }

    // Handle POST requests to create a modele
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Create a new modele if provided
        if(isset($data['modele_onduleur']) || isset($_POST['modele_onduleur'])){
            $modele = isset($data['modele_onduleur']) ? $data['modele_onduleur'] : $_POST['modele_onduleur'];
            $response = createModeleOnduleur($db, $modele);
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Modele already exists']);
                return;
            }
        }

        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle PUT requests to update a modele
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Update modele if ID and new name are provided
        if(isset($_GET['id']) && isset($data['modele-onduleur'])){
            $id = $_GET['id'];
            $modele = $data['modele-onduleur'];
            $response = updateModeleOnduleur($db, $id, $modele);
            if($response){
                echo json_encode($response);
                return;
            }

        }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    // Handle DELETE requests to remove a modele
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            if(deleteModeleOnduleur($db, $id)){
                echo json_encode(['message' => 'Modele successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }

