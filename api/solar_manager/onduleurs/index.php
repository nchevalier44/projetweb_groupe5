<?php
    require_once '../database.php';
    require_once '../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    
    // GET METHOD: Retrieve onduleur(s) based on query parameters
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        // If 'id' is set, return the onduleur with that id
        if(isset($_GET['id'])){
            echo json_encode(getOnduleurParId($db, $_GET['id']));
            return;
        }
        // If both 'id_modele_onduleur' and 'id_marque_onduleur' are set, return the onduleur id matching those
        else if (isset($_GET['id_modele_onduleur']) && !empty($_GET['id_modele_onduleur']) && isset($_GET['id_marque_onduleur']) && !empty($_GET['id_marque_onduleur'])){
            $id_modele = $_GET['id_modele_onduleur'];
            $id_marque = $_GET['id_marque_onduleur'];
            $idOnduleur = getIdOnduleurParIds($db, $id_marque, $id_modele);
            if($idOnduleur){
                echo json_encode($idOnduleur);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Onduleur not found']);
                return;
            }
        }
        // Otherwise, return all onduleurs
        else{
            echo json_encode(getOnduleurs($db));
            return;
        }

    // POST METHOD: Create a new onduleur
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Check if required parameters are set in JSON or POST data
        if((isset($data['id_modele_onduleur']) || isset($_POST['id_modele_onduleur'])) && (isset($data['id_marque_onduleur']) || isset($_POST['id_marque_onduleur']))){

            $id_marque = isset($data['id_marque_onduleur']) ? $data['id_marque_onduleur'] : $_POST['id_marque_onduleur'];
            $id_modele = isset($data['id_modele_onduleur']) ? $data['id_modele_onduleur'] : $_POST['id_modele_onduleur'];

            $response = createOnduleur($db, $id_marque, $id_modele);

            // If creation is successful, return the new id
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                // If onduleur already exists, return error
                http_response_code(400);
                echo json_encode(['error' => 'Onduleur already exists']);
                return;
            }
        }

        // If required parameters are missing, return bad request
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;


    // PUT METHOD: Update an existing onduleur
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        // Check if 'id' is set in query and at least one of the update fields is present
        if(isset($_GET['id']) && (isset($data['id-marque-onduleur']) || isset($data['id-modele-onduleur']))){
            $id = $_GET['id'];
            $id_marque = isset($data['id-marque-onduleur']) ? $data['id-marque-onduleur'] : null;
            $id_modele = isset($data['id-modele-onduleur']) ? $data['id-modele-onduleur'] : null;
            $response = updateOnduleur($db, $id, $id_marque, $id_modele);
            if($response){
                echo json_encode($response);
                return;
            }
        }
        // If required parameters are missing, return bad request
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;


    // DELETE METHOD: Delete an onduleur by id
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $response = deleteOnduleur($db, $id);
            if($response){
                echo json_encode(['message' => 'Onduleur deleted successfully']);
                return;
            }
        // If id is missing or deletion fails, return bad request
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    }

    }