<?php

    // Include database connection and panel functions
    require_once '../database.php';
    require_once '../functions_panneaux.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    // Handle GET requests
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        // If 'id' is provided, return the panel with that id
        if(isset($_GET['id'])){
            echo json_encode(getPanneauParId($db, $_GET['id']));
            return;
        // If both 'id_modele_panneau' and 'id_marque_panneau' are provided, return the matching panel
        }else if(isset($_GET['id_modele_panneau']) && isset($_GET['id_marque_panneau'])){
            $id_modele = $_GET['id_modele_panneau'];
            $id_marque = $_GET['id_marque_panneau'];
            $panneau = getPanneauParIdModeleEtMarque($db, $id_modele, $id_marque);
            if($panneau){
                echo json_encode($panneau);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Panneau not found']);
                return;
            }
        // Otherwise, return all panels
        }else{
            echo json_encode(getPanneaux($db));
            return;
        }


    // Handle POST requests (create a new panel)
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Check if required parameters are present in JSON or POST data
        if((isset($data['id_modele_panneau']) || isset($_POST['id_modele_panneau'])) && (isset($data['id_marque_panneau']) || isset($_POST['id_marque_panneau']))){

            $id_marque = isset($data['id_marque_panneau']) ? $data['id_marque_panneau'] : $_POST['id_marque_panneau'];
            $id_modele = isset($data['id_modele_panneau']) ? $data['id_modele_panneau'] : $_POST['id_modele_panneau'];

            $response = createPanneau($db, $id_marque, $id_modele);

            // If creation is successful, return the new panel id
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                // If panel already exists, return error
                http_response_code(response_code: 400);
                echo json_encode(['error' => 'Panneau already exists']);
                return;
            }
        }

        // If required parameters are missing, return bad request error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;


    // Handle PUT requests (update an existing panel)
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Check if 'id' is provided and at least one field to update
        if(isset($_GET['id']) && (isset($data['id-marque-panneau']) || isset($data['id-modele-panneau']))){
            $id = $_GET['id'];
            $id_marque = isset($data['id-marque-panneau']) ? $data['id-marque-panneau'] : null;
            $id_modele = isset($data['id-modele-panneau']) ? $data['id-modele-panneau'] : null;
            $response = updatePanneau($db, $id, $id_marque, $id_modele);
            if($response){
                echo json_encode($response);
                return;
            }
        }
        // If required parameters are missing, return bad request error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;


    // Handle DELETE requests (delete a panel)
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        // Check if 'id' is provided
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $response = deletePanneau($db, $id);
            // If deletion is successful, return success message
            if($response){
                echo json_encode(['message' => 'Panneau deleted successfully']);
                return;
            }
        // If 'id' is missing or deletion failed, return bad request error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    }

    }