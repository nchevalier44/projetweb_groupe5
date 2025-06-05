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

// 16. Liste des régions disponibles (pour fillSelect)
function getRegionsDisponibles($db){
    $stmt = $db->query("
        SELECT DISTINCT r.Reg_nom AS region
        FROM region r
        JOIN departement d ON r.id = d.id_region
        JOIN ville v ON d.id = v.id
        JOIN localisation l ON v.code_insee = l.code_insee
        JOIN installation i ON i.id_localisation = l.id
        ORDER BY r.Reg_nom
    ");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
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