<?php

// Get all distinct installation years, ordered by year
function getAnnees($db) {
    $stmt = $db->query("SELECT DISTINCT An_installation AS annee
        FROM installation
        ORDER BY annee");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}