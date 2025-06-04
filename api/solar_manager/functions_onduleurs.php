<?php

function getOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM onduleur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM onduleur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 5. Nombre de marques d’onduleurs (marque_onduleur.Onduleur_marque)
function getNbMarquesOnduleurs($db){
    $stmt = $db->query("SELECT COUNT(DISTINCT Onduleur_marque) AS nombre_marques_onduleurs FROM marque_onduleur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}