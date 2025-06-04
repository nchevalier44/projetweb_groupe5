<?php
    require_once '../database.php';
    require_once '../functions_installateurs.php';
    $db = connectDB();
    header('Content-Type: application/json');
    

    //GET METHOD
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['id'])){
            return getInstallateurParId($db, $_GET['id']);
        } else{
            return getInstallateurs($db);
        }

    //POST METHOD
    } else if($_SERVER['REQUEST_METHOD'] == "POST"){
        

    //PUT METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){







    //DELETE METHOD
    } else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    }

