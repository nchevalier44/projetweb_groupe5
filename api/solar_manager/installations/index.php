<?php
    require_once '../database.php';
    require_once '../functions_installations.php'; // Functions for installations

    $db = connectDB();
    header('Content-Type: application/json');
    

    // Handle GET requests for installations
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $parameters = [];
        // Get installation by ID if only 'id' is provided
        if(isset($_GET['id']) && count($_GET) == 1){

            $response = getInformationInstallationParId($db, $_GET['id']);
            if($response){
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Installation not found']);
            }
            return;
        }
        // Add filters if present in query parameters
        if(isset($_GET['id-marque-onduleur']) && !empty($_GET['id-marque-onduleur'])){
            $parameters['id_marque_onduleur'] = explode(',', $_GET['id-marque-onduleur']);
        } 
        if(isset($_GET['id-marque-panneau']) && !empty($_GET['id-marque-panneau'])){
            $parameters['id_marque_panneau'] = explode(',', $_GET['id-marque-panneau']);
        } 
        if(isset($_GET['id-departement']) && !empty($_GET['id-departement'])){
            $parameters['departement.id'] = explode(',', $_GET['id-departement']);
        }
        if(isset($_GET['id-region']) && !empty($_GET['id-region'])){
            $parameters['region.id'] = explode(',', $_GET['id-region']);
        }
        if(isset($_GET['annee']) && !empty($_GET['annee'])){
            $parameters['An_installation'] = explode(',', $_GET['annee']);
        }
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $parameters['limit'] = $_GET['limit'];
        }
        if(isset($_GET['offset']) && !empty($_GET['offset'])){
            $parameters['offset'] = $_GET['offset'];
        }

        // Return filtered installations
        echo json_encode(getInstallationsFilters($db, $parameters));
        return;


    // Handle POST requests to add a new installation
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $data = json_decode(file_get_contents("php://input"), true);
        // Check for required fields
        if (isset($data['Iddoc']) && isset($data['Mois_installation']) && isset($data['An_installation']) && isset($data['Nb_panneaux']) &&
            isset($data['Nb_onduleurs']) && isset($data['Puissance_crete']) && isset($data['Surface']) && isset($data['Pente']) && 
            isset($data['Orientation']) && isset($data['id_localisation']) && isset($data['id_installateur']) &&
            isset($data['id_onduleur']) && isset($data['id_panneau'])) {
            echo json_encode(addInstallation($db, $data));
            return;
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields."]);
            return;
        }

        


    // Handle PUT requests to update an installation
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            echo json_encode(updateInstallation($db, $data));
            return;
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID is required for update."]);
            return;
        }



    // Handle DELETE requests (not implemented)
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        // Check if 'id' is provided
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $response = deleteInstallation($db, $id);
            // If deletion is successful, return success message
            if($response){
                echo json_encode(['message' => 'Installation deleted successfully']);
                return;
            }
        }
        // If 'id' is missing or deletion failed, return bad request error
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }