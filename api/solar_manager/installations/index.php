<?php
    require_once '../database.php';
    require_once '../functions_installations.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $parameters = array();
        if(isset($_GET['id'])){
            $parameters['id'] = $_GET['id'];
        } 
        else if(isset($_GET['id-marque-onduleur'])){
            $parameters['id_marque_onduleur'] = $_GET['id-marque-onduleur'];
        } 
        else if(isset($_GET['id-marque-panneau'])){
            $parameters['id_marque_panneau'] = $_GET['id-marque-panneau'];
        } 
        else if(isset($_GET['id-departement'])){
            $parameters['departement.id'] = $_GET['id-departement'];
        }

        echo json_encode(getInstallationsFilters($db, $parameters));
        return;


    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){


    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){







    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    }

