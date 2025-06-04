<?php
    require_once '../database.php';
    require_once '../functions_localisations.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            return getLocalisationParId($db, $_GET['id']);
        } else{
            return getLocalisations($db);
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){


    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){







    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    }

