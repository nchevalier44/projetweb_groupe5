<?php

// Get all onduleurs with their brand and model info
function getOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM onduleur o
    JOIN marque_onduleur marque ON o.id_marque_onduleur = marque.id
    JOIN modele_onduleur modele ON o.id_modele_onduleur = modele.id
    ORDER BY Onduleur_marque, Onduleur_modele");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get an onduleur by its ID
function getOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM onduleur o 
    JOIN marque_onduleur marque ON o.id_marque_onduleur = marque.id
    JOIN modele_onduleur modele ON o.id_modele_onduleur = modele.id
    WHERE o.id = :id
    ORDER BY Onduleur_marque, Onduleur_modele");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get the number of unique onduleur brands
function getNbMarquesOnduleurs($db){
    $stmt = $db->query("SELECT COUNT(DISTINCT Onduleur_marque) AS nombre_marques_onduleurs FROM marque_onduleur");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Create a new onduleur if it doesn't already exist
function createOnduleur($db, $id_marque, $id_modele) {
    // Check if the onduleur already exists
    if(!getIdOnduleurParIds($db, $id_marque, $id_modele)){
        $stmt = $db->prepare("INSERT INTO onduleur (id_marque_onduleur, id_modele_onduleur) VALUES (:id_marque, :id_modele)");
        $stmt->bindParam(':id_marque', $id_marque);
        $stmt->bindParam(':id_modele', $id_modele);
        if ($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }
}

// Update an onduleur's brand and/or model
function updateOnduleur($db, $id, $id_marque, $id_modele) {
    $fields = [];
    $params = [':id' => $id];

    if ($id_marque != null) {
        $fields[] = 'id_marque_onduleur = :id_marque';
        $params[':id_marque'] = $id_marque;
    }
    if ($id_modele != null) {
        $fields[] = 'id_modele_onduleur = :id_modele';
        $params[':id_modele'] = $id_modele;
    }

    //Don't update if no fields are provided
    if (empty($fields)) {
        return false;
    }

    $query = "UPDATE onduleur SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $db->prepare($query);

    //Binding params
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    if ($stmt->execute()) {
        return getOnduleurParId($db, $id);
    }
    return false;
}

// Delete an onduleur by its ID
function deleteOnduleur($db, $id) {
    try{
        $stmt = $db->prepare("DELETE FROM onduleur WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }  catch (\Exception $e) {
        return false;
}
}

// Get an onduleur by brand and model IDs
function getIdOnduleurParIds($db, $id_marque, $id_modele) {
    $stmt = $db->prepare("SELECT * FROM onduleur WHERE id_marque_onduleur = :id_marque AND id_modele_onduleur = :id_modele");
    $stmt->bindParam(':id_marque', $id_marque, PDO::PARAM_INT);
    $stmt->bindParam(':id_modele', $id_modele, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//////////////////////Marque Onduleur/////////////////////////////////

// Get an onduleur brand by its ID
function getMarqueOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM marque_onduleur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all onduleur brands
function getMarquesOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM marque_onduleur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create a new onduleur brand if it doesn't already exist
function createMarqueOnduleur($db, $marque) {
    if(!getIdMarqueOnduleurParMarque($db, $marque)){
        $stmt = $db->prepare("INSERT INTO marque_onduleur (Onduleur_marque) VALUES (:marque)");
        $stmt->bindParam(':marque', $marque);
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }
}

// Get a brand ID by its name
function getIdMarqueOnduleurParMarque($db, $marque){
    $stmt = $db->prepare("SELECT id FROM marque_onduleur WHERE Onduleur_marque = :marque");
    $stmt->bindParam(':marque', $marque);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update an onduleur brand's name
function updateMarqueOnduleur($db, $id, $marque) {
    $stmt = $db->prepare("UPDATE marque_onduleur SET Onduleur_marque = :marque WHERE id = :id");
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        return getMarqueOnduleurParId($db, $id);
    }
    return false;
}

// Delete an onduleur brand by its ID
function deleteMarqueOnduleur($db, $id) {
    try{
        $stmt = $db->prepare("DELETE FROM marque_onduleur WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (\Exception $e) {
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

//////////////////////Modele Onduleur/////////////////////////////////

// Get an onduleur model by its ID
function getModeleOnduleurParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM modele_onduleur WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all onduleur models
function getModelesOnduleurs($db) {
    $stmt = $db->query("SELECT * FROM modele_onduleur");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create a new onduleur model if it doesn't already exist
function createModeleOnduleur($db, $modele) {
    if(!getIdModeleOnduleurParModele($db, $modele)){
        $stmt = $db->prepare("INSERT INTO modele_onduleur (Onduleur_modele) VALUES (:modele)");
        $stmt->bindParam(':modele', $modele);
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }
}

// Get a model ID by its name
function getIdModeleOnduleurParModele($db, $modele) {
    $stmt = $db->prepare("SELECT id FROM modele_onduleur WHERE Onduleur_modele = :modele");
    $stmt->bindParam(':modele', $modele);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update an onduleur model's name
function updateModeleOnduleur($db, $id, $modele) {
    $stmt = $db->prepare("UPDATE modele_onduleur SET Onduleur_modele = :modele WHERE id = :id");
    $stmt->bindParam(':modele', $modele);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        return getModeleOnduleurParId($db, $id);
    }
    return false;
}

// Delete an onduleur model by its ID
function deleteModeleOnduleur($db, $id) {
    try{
        $stmt = $db->prepare("DELETE FROM modele_onduleur WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (\Exception $e) {
        return false;
    } catch (PDOException $e) {
        return false;
    }

}