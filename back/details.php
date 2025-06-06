<?php 
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

include_once "../html/details.php"; 
?>

<script src="../scripts/detailsback.js"></script>
<script src="../scripts/back.js"></script>