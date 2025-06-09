<?php 
// Start session and check authentication
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

// Include the map HTML page for the back office
include_once "../html/carte.php";

?>

<!-- Include back office JS logic -->
<script src = "../scripts/back.js"></script>