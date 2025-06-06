<?php

function getVilles($db)
{
    $stmt = $db->query("SELECT * FROM ville ORDER BY Nom_standard");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVilleParId($db, $id)
{
    $stmt = $db->prepare("SELECT * FROM ville WHERE code_insee = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
