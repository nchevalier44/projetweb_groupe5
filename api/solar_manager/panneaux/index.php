<?php

    require_once '../database.php';
    require_once '../functions_panneaux.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            echo json_encode(getPanneauParId($db, $_GET['id']));
            return;
        }else if(isset($_GET['id_modele_panneau']) && isset($_GET['id_marque_panneau'])){
            $id_modele = htmlspecialchars($_GET['id_modele_panneau']);
            $id_marque = htmlspecialchars($_GET['id_marque_panneau']);
            $panneau = getPanneauParIdModeleEtMarque($db, $id_modele, $id_marque);
            if($panneau){
                echo json_encode($panneau);
                return;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Panneau not found']);
                return;
            }
        }else{
            echo json_encode(getPanneaux($db));
            return;
        }


    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if((isset($data['id_modele_panneau']) || isset($_POST['id_modele_panneau'])) && (isset($data['id_marque_panneau']) || isset($_POST['id_marque_panneau']))){

            $id_marque = isset($data['id_marque_panneau']) ? htmlspecialchars($data['id_marque_panneau']) : htmlspecialchars($_POST['id_marque_panneau']);
            $id_modele = isset($data['id_modele_panneau']) ? htmlspecialchars($data['id_modele_panneau']) : htmlspecialchars($_POST['id_modele_panneau']);

            $response = createPanneau($db, $id_marque, $id_modele);

            if($response){
                echo json_encode(['id' => $response]);
                return;
            } else {
                http_response_code(response_code: 400);
                echo json_encode(['error' => 'Panneau already exists']);
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
        if(isset($_GET['id']) && (isset($data['id-marque-panneau']) || isset($data['id-modele-panneau']))){
            $id = htmlspecialchars($_GET['id']);
            $id_marque = isset($data['id-marque-panneau']) ? htmlspecialchars($data['id-marque-panneau']) : null;
            $id_modele = isset($data['id-modele-panneau']) ? htmlspecialchars($data['id-modele-panneau']) : null;
            $response = updatePanneau($db, $id, $id_marque, $id_modele);
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
            $response = deletePanneau($db, $id);
            if($response){
                echo json_encode(['message' => 'Panneau deleted successfully']);
                return;
            }
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    }

    }