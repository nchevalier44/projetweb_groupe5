<?php

// Function to check if an installateur exists by name
function installateurExists($db, $name) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM installateur WHERE Installateur = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}
function getInstallateurs($db) {
    $stmt = $db->query("SELECT * FROM installateur ORDER BY Installateur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getInstallateurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM installateur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//POST method to create a new installateur
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

//PUT method to update an installateur
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

//DELETE method to delete an installateur
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

function getNbInstallateurs($db){
    $stmt = $db->query("SELECT COUNT(*) AS nombre_installateurs FROM installateur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}