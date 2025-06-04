<?php

function getPanneaux($db) {
    $stmt = $db->query("SELECT * FROM panneau");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM panneau WHERE id = :id");
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