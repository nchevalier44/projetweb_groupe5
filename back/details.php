<?php 
// Start session and check authentication
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

// Include the details HTML page for the back office
include_once "../html/details.php"; 
?>

<!-- Include back office JS logic for details and general back office -->
<script src="../scripts/detailsback.js" type="module"></script>
<script src="../scripts/back.js"></script>