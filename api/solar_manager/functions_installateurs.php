<?php

// Check if an installateur exists by name
function installateurExists($db, $name) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM installateur WHERE Installateur = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

// Get all installateurs, ordered by name
function getInstallateurs($db) {
    $stmt = $db->query("SELECT * FROM installateur ORDER BY Installateur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get an installateur by its ID
function getInstallateurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM installateur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get the ID of an installateur by its name
function getIdInstallateurParInstallateur($db, $installateur) {
    $stmt = $db->prepare("SELECT id FROM installateur WHERE Installateur = :installateur");
    $stmt->bindParam(':installateur', $installateur);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Create a new installateur or return the existing one if it already exists
function createInstallateur($db, $data) {
    if (installateurExists($db, $data['Installateur'])) {
        // Return the ID of the existing installateur
        $stmt = $db->prepare("SELECT id FROM installateur WHERE Installateur = :name");
        $stmt->bindParam(':name', $data['Installateur']);
        $stmt->execute();
        $existingId = $stmt->fetchColumn();
        return ['status' => 'exists', 'id' => $existingId];
    }
    
    $stmt = $db->prepare("INSERT INTO installateur (Installateur) VALUES (:name)");
    $stmt->bindParam(':name', $data['Installateur']);

    try {
        if ($stmt->execute()) {
            return ['status' => 'success', 'id' => $db->lastInsertId()];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ['status' => 'error', 'message' => 'SQL Error: ' . $errorInfo[2]];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Update an installateur's name by its ID
function updateInstallateur($db, $data) {
    $stmt = $db->prepare("UPDATE installateur SET Installateur = :name WHERE id = :id");
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':name', $data['Installateur']);

    try {
        if ($stmt->execute()) {
            return ['status' => 'success', 'id' => $db->lastInsertId()];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ['status' => 'error', 'message' => 'SQL Error: ' . $errorInfo[2]];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Delete an installateur by its ID
function deleteInstallateur($db, $id) {
    $stmt = $db->prepare("DELETE FROM installateur WHERE id = :id");
    $stmt->bindParam(':id', $id);

    try {
        if ($stmt->execute()) {
            return ['status' => 'success', 'id' => $db->lastInsertId()];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ['status' => 'error', 'message' => 'SQL Error: ' . $errorInfo[2]];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Get the total number of installateurs
function getNbInstallateurs($db){
    $stmt = $db->query("SELECT COUNT(*) AS nombre_installateurs FROM installateur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}