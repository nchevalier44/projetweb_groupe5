<?php

session_start();
//If the user is already logged
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    session_destroy();
    header('Location: ../html/index.php');
    exit();
}