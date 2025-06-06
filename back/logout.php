<?php

session_start();
//If the user is already logged
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    session_destroy();
    header('Location: ../html/index.php?logout');
    exit();
}

if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header('Location: ../html/index.php');
    exit();
}