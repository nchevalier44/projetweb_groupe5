<?php
    require_once '../../database.php';
    require_once '../../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            $marque = getMarqueOnduleurParId($db, $_GET['id']);
            if($marque){
                echo json_encode($marque);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Marque not found']);
            }
            return;
        } else{
            echo json_encode(getMarquesOnduleurs($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if(isset($data['marque-onduleur']) || isset($_POST['marque-onduleur'])){
            $marque = isset($data['marque-onduleur']) ? htmlspecialchars($data['marque-onduleur']) : htmlspecialchars($_POST['marque-onduleur']);
            $response = createMarqueOnduleur($db, $marque);
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
        if(isset($_GET['id']) && isset($data['marque-onduleur'])){
            $id = htmlspecialchars($_GET['id']);
            $marque = htmlspecialchars($data['marque-onduleur']);
            $response = updateMarqueOnduleur($db, $id, $marque);
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
            if(deleteMarqueOnduleur($db, $id)){
                echo json_encode(['message' => 'Marque successfully deleted']);
                return;
            }
        } 
        http_response_code(400);
        echo json_encode(['error' => 'Bad request']);
        return;
    }