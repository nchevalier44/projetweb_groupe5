<?php

// Start session and handle logout logic
session_start();
// If the user is logged in, destroy session and redirect with logout flag
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    session_destroy();
    header('Location: ../html/index.php?logout');
    exit();
}

// If not logged in, redirect to previous page or index
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header('Location: ../html/index.php');
    exit();
}