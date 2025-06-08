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
        }
        else if (isset($_GET['id_modele_onduleur']) && !empty($_GET['id_modele_onduleur']) && isset($_GET['id_marque_onduleur']) && !empty($_GET['id_marque_onduleur'])){
            $id_modele = htmlspecialchars($_GET['id_modele_onduleur']);
            $id_marque = htmlspecialchars($_GET['id_marque_onduleur']);
            $idOnduleur = getIdOnduleurParIds($db, $id_marque, $id_modele);
            if($idOnduleur){
                echo json_encode(['id' => $idOnduleur]);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Onduleur not found']);
                return;
            }
        }
        else{
            echo json_encode(getOnduleurs($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if((isset($data['id_modele_onduleur']) || isset($_POST['id_modele_onduleur'])) && (isset($data['id_marque_onduleur']) || isset($_POST['id_marque_onduleur']))){

            $id_marque = isset($data['id_marque_onduleur']) ? htmlspecialchars($data['id_marque_onduleur']) : htmlspecialchars($_POST['id_marque_onduleur']);
            $id_modele = isset($data['id_modele_onduleur']) ? htmlspecialchars($data['id_modele_onduleur']) : htmlspecialchars($_POST['id_modele_onduleur']);

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
        if(isset($_GET['id']) && (isset($data['id-marque-onduleur']) || isset($data['id-modele-onduleur']))){
            $id = htmlspecialchars($_GET['id']);
            $id_marque = isset($data['id-marque-onduleur']) ? htmlspecialchars($data['id-marque-onduleur']) : null;
            $id_modele = isset($data['id-modele-onduleur']) ? htmlspecialchars($data['id-modele-onduleur']) : null;
            $response = updateOnduleur($db, $id, $id_marque, $id_modele);
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
            $response = deleteOnduleur($db, $id);
            if($response){
                echo json_encode(['message' => 'Onduleur deleted successfully']);
                return;
            }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    }

    }