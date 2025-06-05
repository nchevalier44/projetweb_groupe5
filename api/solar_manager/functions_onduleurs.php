<?php

function getOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM onduleur o
    JOIN marque_onduleur marque ON o.id_marque_onduleur = marque.id
    JOIN modele_onduleur modele ON o.id_modele_onduleur = modele.id
    ORDER BY Onduleur_marque, Onduleur_modele");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM onduleur o 
    JOIN marque_onduleur marque ON o.id_marque_onduleur = marque.id
    JOIN modele_onduleur modele ON o.id_modele_onduleur = modele.id
    WHERE o.id = :id
    ORDER BY Onduleur_marque, Onduleur_modele");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 5. Nombre de marques d’onduleurs (marque_onduleur.Onduleur_marque)
function getNbMarquesOnduleurs($db){
    $stmt = $db->query("SELECT COUNT(DISTINCT Onduleur_marque) AS nombre_marques_onduleurs FROM marque_onduleur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//////////////////////Marque Onduleur/////////////////////////////////
function getMarqueOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM marque_onduleur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMarquesOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM marque_onduleur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//////////////////////Modele Onduleur/////////////////////////////////
function getModeleOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM modele_onduleur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getModelesOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM modele_onduleur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}