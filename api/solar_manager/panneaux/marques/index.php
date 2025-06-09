<?php
    require_once '../../database.php';
    require_once '../../functions_panneaux.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            // Get marque by ID
            $marque = getMarquePanneauParId($db, htmlspecialchars($_GET['id']));
            if($marque){
                echo json_encode($marque);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Marque not found']);
            }
            return;
        } 
        else if (isset($_GET['Marque_panneau']) && !empty($_GET['Marque_panneau'])){
            // Get marque ID by name
            $marque = htmlspecialchars($_GET['Marque_panneau']);
            $idMarque = getIdMarquePanneauParMarque($db, $marque);
            if($idMarque){
                echo json_encode($idMarque);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Marque not found']);
                return;
            }
        }else{
            // Get all marques
            echo json_encode(getMarquesPanneaux($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Create a new marque if provided
        if(isset($data['marque_panneau']) || isset($_POST['marque_panneau'])){
            $marque = isset($data['marque_panneau']) ? htmlspecialchars($data['marque_panneau']) : htmlspecialchars($_POST['marque-panneau']);
            $response = createMarquePanneau($db, $marque);
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

    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Update marque if ID and new name are provided
        if(isset($_GET['id']) && isset($data['marque-panneau'])){
            $id = $data['id'];
            $marque = $data['marque-panneau'];
            $response = updateMarquePanneau($db, $id, $marque);
            if($response){
                echo json_encode($response);
                return;
            }
        }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($_GET['id'])){
            // Delete marque by ID
            $id = $_GET['id'];
            if(deleteMarquePanneau($db, $id)){
                echo json_encode(['message' => 'Marque successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }