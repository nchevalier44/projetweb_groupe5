<?php

session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: ../html/index.php');
    exit();
}

include_once "../html/recherche.php";
?>
<script src="../scripts/rechercheback.js"></script>
<script src="../scripts/back.js"></script>