<?php

// Get all regions, ordered by region name
function getRegions($db)
{
    $stmt = $db->query("SELECT * FROM region ORDER BY Reg_nom");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a region by its ID
function getRegionParId($db, $id)
{
    $stmt = $db->prepare("SELECT * FROM Region WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
