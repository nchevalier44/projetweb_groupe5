<?php
    require_once '../../database.php';
    require_once '../../functions_panneaux.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            $modele = getModelePanneauParId($db, htmlspecialchars($_GET['id']));
            if($modele){
                echo json_encode($modele);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
            }
            return;
        } else{
            echo json_encode(getModelesPanneaux($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if(isset($data['modele-panneau']) || isset($_POST['modele-panneau'])){
            $modele = isset($data['modele-panneau']) ? htmlspecialchars($data['modele-panneau']) : htmlspecialchars($_POST['modele-panneau']);
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

        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;

    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        if(isset($_GET['id']) && isset($data['modele-panneau'])){
            $id = htmlspecialchars($_GET['id']);
            $modele = htmlspecialchars($data['modele-panneau']);
            $response = updateModelePanneau($db, $id, $modele);
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
            if(deleteModelePanneau($db, $id)){
                echo json_encode(['message' => 'Modele successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }

