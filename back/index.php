<?php 
// Start session and check authentication
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

// Include the main index HTML page for the back office
include_once "../html/index.php";

?>

<!-- Include back office JS logic and index-specific logic -->
<script src = "../scripts/back.js"></script>
<script src = "../scripts/indexback.js"></script>