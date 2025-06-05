<?php

function getLocalisations($db) {
    $stmt = $db->query("SELECT * FROM localisation");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLocalisationParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM localisation WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 14. Localisation des installations (Lat, Lon)
function getLocalisationInstallations($db){
    $stmt = $db->query("
        SELECT i.id AS id_installation, l.Lat AS latitude, l.Lon AS longitude
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}