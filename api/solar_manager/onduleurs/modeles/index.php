<?php
    require_once '../../database.php';
    require_once '../../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            $modele = getModeleOnduleurParId($db, $_GET['id']);
            if($modele){
                echo json_encode($modele);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Modele not found']);
            }
            return;
        } else{
            echo json_encode(getModelesOnduleurs($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if(isset($data['modele-onduleur']) || isset($_POST['modele-onduleur'])){
            $modele = isset($data['modele-onduleur']) ? htmlspecialchars($data['modele-onduleur']) : htmlspecialchars($_POST['modele-onduleur']);
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

    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        if(isset($_GET['id']) && isset($data['modele-onduleur'])){
            $id = htmlspecialchars($_GET['id']);
            $modele = htmlspecialchars($data['modele-onduleur']);
            $response = updateModeleOnduleur($db, $id, $modele);
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
            if(deleteModeleOnduleur($db, $id)){
                echo json_encode(['message' => 'Modele successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }

