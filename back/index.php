<?php 
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

include_once "../html/index.php";

?>

<script src = "../scripts/back.js"></script>
<script src = "../scripts/indexback.js"></script>