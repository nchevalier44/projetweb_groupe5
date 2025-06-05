<?php
    require_once '../database.php';
    require_once '../functions_localisations.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            echo json_encode(getLocalisationParId($db, $_GET['id']));
            return;
        } else{
            echo json_encode(getLocalisations($db));
            return;
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){


    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){







    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    }

