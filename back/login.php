<?php

session_start();
//If the user is already logged
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header('Location: index.php');
    exit();
} else{
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the username and password are correct
        if (md5($username) === '21232f297a57a5a743894a0e4a801fc3' && md5($password) === '1bc21a255272822ee2d7d49d13dc726b') {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            header('Location: index.php');
            exit();
        } else {
            if(isset($_SERVER['HTTP_REFERER'])){
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?login-error=1');
                exit();
            } else{
                header('Location: ../html/index.php?login-error=1');
                exit();
            }
        }
    }
}