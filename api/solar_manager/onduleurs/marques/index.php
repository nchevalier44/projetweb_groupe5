<?php
    require_once '../database.php';
    require_once '../functions_onduleurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            echo json_encode(getMarqueOnduleurParId($db, $_GET['id']));
            return;
        } else{
            echo json_encode(getMarquesOnduleurs($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){


    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){







    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    }

