<?php

function getInstallateurs($db) {
    $stmt = $db->query("SELECT * FROM installateur ORDER BY installateur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getInstallateurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM installateur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNbInstallateurs($db){
    $stmt = $db->query("SELECT COUNT(*) AS nombre_installateurs FROM installateur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}