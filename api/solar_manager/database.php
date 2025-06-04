<?php

function connectDB()
{
    $host = 'localhost';
    $db = 'projectweb';
    $user = 'groupe5';
    $pass = 'groupe5';
    $dsn = "pgsql:host=$host;dbname=$db";

    try {
        return new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


