<?php
//vérification que la localisation existe
function localisationExists($db, $lat, $lon)
{
    // Vérifier par coordonnées (avec une petite marge de tolérance)
    $stmt = $db->prepare("SELECT COUNT(*) FROM localisation WHERE ABS(Lat - :lat) < 0.001 AND ABS(Lon - :lon) < 0.001");
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lon', $lon);

    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function getLocalisations($db)
{
    $stmt = $db->query("SELECT * FROM localisation");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLocalisationParId($db, $id)
{
    $stmt = $db->prepare("SELECT * FROM localisation WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createLocalisation($db, $data)
{
    if(localisationExists($db, $data['Lat'], $data['Lon'])) {
        //return the ID of the existing localisation
        $stmt = $db->prepare("SELECT id FROM localisation WHERE ABS(Lat - :lat) < 0.001 AND ABS(Lon - :lon) < 0.001");
        $stmt->bindParam(':lat', $data['Lat']);
        $stmt->bindParam(':lon', $data['Lon']);
        $stmt->execute();
        $existingId = $stmt->fetchColumn();
        return ['status' => 'exists', 'id' => $existingId];
    }

    $stmt = $db->prepare("INSERT INTO localisation (Lat, Lon, code_insee) VALUES (:lat, :lon, :code_insee)");
    $stmt->bindParam(':lat', $data['Lat']);
    $stmt->bindParam(':lon', $data['Lon']);
    $stmt->bindParam(':code_insee', $data['code_insee']);

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

function updateLocalisation($db, $data)
{
    $stmt = $db->prepare("UPDATE localisation SET Lat = :lat, Lon = :lon, code_insee = :code_insee WHERE id = :id");
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':lat', $data['Lat']);
    $stmt->bindParam(':lon', $data['Lon']);
    $stmt->bindParam(':code_insee', $data['code_insee']);

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

function deleteLocalisation($db, $id)
{
    $stmt = $db->prepare("DELETE FROM localisation WHERE id = :id");
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


//Localisation des installations (Lat, Lon)
function getLocalisationInstallations($db)
{
    $stmt = $db->query("
        SELECT i.id AS id_installation, l.Lat AS latitude, l.Lon AS longitude
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

