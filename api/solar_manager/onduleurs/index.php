<?php
    require_once '../database.php';
    require_once '../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            echo json_encode(getOnduleurParId($db, $_GET['id']));
            return;
        } else{
            echo json_encode(getOnduleurs($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if((isset($data['id-modele-onduleur']) || isset($_POST['id-modele-onduleur'])) && (isset($data['onduleur']) || isset($_POST['onduleur']))){

            $id_marque = isset($data['id-marque-onduleur']) ? htmlspecialchars($data['id-marque-onduleur']) : htmlspecialchars($_POST['id-marque-onduleur']);
            $id_modele = isset($data['id-modele-onduleur']) ? htmlspecialchars($data['id-modele-onduleur']) : htmlspecialchars($_POST['id-modele-onduleur']);
            $response = createOnduleur($db, $id_marque, $id_modele);
            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Onduleur already exists']);
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
        if(isset($_GET['id']) && isset($data['onduleur'])){
            $id = htmlspecialchars($_GET['id']);
            $onduleur = htmlspecialchars($data['onduleur']);
            $response = updateOnduleur($db, $id, $onduleur);
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
            $id = htmlspecialchars($_GET['id']);
            //$response = deleteOnduleur($db, $id);
            if($response){
                echo json_encode(['message' => 'Onduleur deleted successfully']);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Onduleur not found']);
                return;
            }
        }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    }

