<?php

function getRegions($db)
{
    $stmt = $db->query("SELECT * FROM region ORDER BY Reg_nom");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRegionParId($db, $id)
{
    $stmt = $db->prepare("SELECT * FROM Region WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
