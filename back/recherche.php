<?php
// Start session and check authentication
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

// Include the search HTML page for the back office
include_once "../html/recherche.php";
?>
<!-- Include back office JS logic for search and general back office -->
<script src="../scripts/rechercheback.js"></script>
<script src="../scripts/back.js"></script>