<?php

function getPanneaux($db) {
    $stmt = $db->query("SELECT * FROM panneau p
    JOIN marque_panneau marque ON p.id_marque_panneau = marque.id
    JOIN modele_panneau modele ON p.id_modele_panneau = modele.id
    ORDER BY Panneaux_marque, Panneaux_modele");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM panneau p
    JOIN marque_panneau marque ON p.id_marque_panneau = marque.id
    JOIN modele_panneau modele ON p.id_modele_panneau = modele.id
    WHERE p.id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// 6. Nombre de marques de panneaux (marque_panneau.Panneaux_marque)
function getNbMarquesPanneaux($db){
    $stmt = $db->query("SELECT COUNT(DISTINCT Panneaux_marque) AS nombre_marques_panneaux FROM marque_panneau");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 11. Nombre total de panneaux installés
function getNbTotalPanneauxInstalles($db){
    $stmt = $db->query("SELECT SUM(Nb_panneaux) AS total_panneaux FROM installation");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//////////////////////Marque Panneau/////////////////////////////////
function getMarquePanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM marque_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getIdMarquePanneauParMarque($db, $marque){
    $stmt = $db->prepare("SELECT id FROM marque_panneau WHERE Panneaux_marque = :marque");
    $stmt->bindParam(':marque', $marque);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getMarquesPanneaux($db) {
    $stmt = $db->query("SELECT * FROM marque_panneau");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMarquePanneau($db, $marque) {
    if(!getIdMarquePanneauParMarque($db, $marque)){
        $stmt = $db->prepare("INSERT INTO marque_panneau (Panneaux_marque) VALUES (:marque)");
        $stmt->bindParam(':marque', $marque);
        if($stmt->execute()){
            return $db->lastInsertId();
        } else{
            return false;
        }
        
    }
}

function updateMarquePanneau($db, $id, $marque){
    $stmt = $db->prepare("UPDATE marque_panneau SET Panneaux_marque = :marque WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':marque', $marque);
    if($stmt->execute()){
        return ['id' => $id, 'marque' => $marque];
    } else{
        return false;
    }
}

function deleteMarquePanneau($db, $id) {
    $stmt = $db->prepare("DELETE FROM marque_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}


//////////////////////Modele Panneau/////////////////////////////////
function getModelePanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM modele_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getModelesPanneaux($db) {
    $stmt = $db->query("SELECT * FROM modele_panneau");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createModelePanneau($db, $modele) {
    if(!getIdModelePanneauParModele($db, $modele)){
        $stmt = $db->prepare("INSERT INTO modele_panneau (Panneaux_modele) VALUES (:modele)");
        $stmt->bindParam(':modele', $modele);
        if($stmt->execute()){
            return $db->lastInsertId();
        } else{
            return false;
        }
        
    }
}

function getIdModelePanneauParModele($db, $modele){
    $stmt = $db->prepare("SELECT id FROM modele_panneau WHERE Panneaux_modele = :modele");
    $stmt->bindParam(':modele', $modele);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateModelePanneau($db, $id, $modele){
    $stmt = $db->prepare("UPDATE modele_panneau SET Panneaux_modele = :modele WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':modele', $modele);
    if($stmt->execute()){
        return ['id' => $id, 'modele' => $modele];
    } else{
        return false;
    }
}

function deleteModelePanneau($db, $id) {
    $stmt = $db->prepare("DELETE FROM modele_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}