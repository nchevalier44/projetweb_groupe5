<?php
function connectDB()
{
    $host = 'localhost';
    $db = 'solar_manager';
    $user = 'user';
    $pass = 'Isen44';
    $dsn = "mysql:host=$host;dbname=$db;";
    
    try {
        return new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


