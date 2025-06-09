<?php

// Get all departments, ordered by name
function getDepartements($db) {
    $stmt = $db->query("SELECT * FROM departement ORDER BY Dep_nom");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a department by its ID
function getDepartementParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM departement WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}