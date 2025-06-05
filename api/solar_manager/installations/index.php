<?php
    require_once '../database.php';
    require_once '../functions_installations.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $parameters = array();
        if(isset($_GET['id']) && count($_GET) == 1){
            
            echo json_encode(getInformationInstallationParId($db, $_GET['id']));
            return;
        } 
        if(isset($_GET['id-marque-onduleur']) && !empty($_GET['id-marque-onduleur'])){
            $parameters['id_marque_onduleur'] = explode(',', $_GET['id-marque-onduleur']);
        } 
        if(isset($_GET['id-marque-panneau']) && !empty($_GET['id-marque-panneau'])){
            $parameters['id_marque_panneau'] = explode(',', $_GET['id-marque-panneau']);
        } 
        if(isset($_GET['id-departement']) && !empty($_GET['id-departement'])){
            $parameters['departement.id'] = explode(',', $_GET['id-departement']);
        }
        if(isset($_GET['id-region']) && !empty($_GET['id-region'])){
            $parameters['region.id'] = explode(',', $_GET['id-region']);
        }
        if(isset($_GET['annee']) && !empty($_GET['annee'])){
            $parameters['An_installation'] = explode(',', $_GET['annee']);
        }
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $parameters['limit'] = $_GET['limit'];
        }
        if(isset($_GET['offset']) && !empty($_GET['offset'])){
            $parameters['offset'] = $_GET['offset'];
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

