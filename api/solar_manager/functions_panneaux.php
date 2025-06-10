<?php

// Get all panels with their brand and model info
function getPanneaux($db) {
    $stmt = $db->query("SELECT * FROM panneau p
    JOIN marque_panneau marque ON p.id_marque_panneau = marque.id
    JOIN modele_panneau modele ON p.id_modele_panneau = modele.id
    ORDER BY Panneaux_marque, Panneaux_modele");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a panel by its ID
function getPanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM panneau p
    JOIN marque_panneau marque ON p.id_marque_panneau = marque.id
    JOIN modele_panneau modele ON p.id_modele_panneau = modele.id
    WHERE p.id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get a panel by model and brand IDs
function getPanneauParIdModeleEtMarque($db, $id_modele, $id_marque) {
    $stmt = $db->prepare("SELECT * FROM panneau p
    JOIN marque_panneau marque ON p.id_marque_panneau = marque.id
    JOIN modele_panneau modele ON p.id_modele_panneau = modele.id
    WHERE p.id_modele_panneau = :id_modele AND p.id_marque_panneau = :id_marque");
    $stmt->bindParam(':id_modele', $id_modele);
    $stmt->bindParam(':id_marque', $id_marque);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get the number of unique panel brands
function getNbMarquesPanneaux($db){
    $stmt = $db->query("SELECT COUNT(DISTINCT Panneaux_marque) AS nombre_marques_panneaux FROM marque_panneau");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get the total number of installed panels
function getNbTotalPanneauxInstalles($db){
    $stmt = $db->query("SELECT SUM(Nb_panneaux) AS total_panneaux FROM installation");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Create a new panel if it doesn't already exist
function createPanneau($db, $id_marque, $id_modele) {
    // Check if the panneau already exists
    if(!getIdPanneauParIds($db, $id_marque, $id_modele)){
        $stmt = $db->prepare("INSERT INTO panneau (id_marque_panneau, id_modele_panneau) VALUES (:id_marque, :id_modele)");
        $stmt->bindParam(':id_marque', $id_marque);
        $stmt->bindParam(':id_modele', $id_modele);
        if ($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }
}

// Get a panel ID by brand and model IDs
function getIdPanneauParIds($db, $id_marque, $id_modele) {
    $stmt = $db->prepare("SELECT id FROM panneau WHERE id_marque_panneau = :id_marque AND id_modele_panneau = :id_modele");
    $stmt->bindParam(':id_marque', $id_marque, PDO::PARAM_INT);
    $stmt->bindParam(':id_modele', $id_modele, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update a panel's brand and/or model
function updatePanneau($db, $id, $id_marque, $id_modele) {
    $fields = [];
    $params = [':id' => $id];

    if ($id_marque != null) {
        $fields[] = 'id_marque_panneau = :id_marque';
        $params[':id_marque'] = $id_marque;
    }
    if ($id_modele != null) {
        $fields[] = 'id_modele_panneau = :id_modele';
        $params[':id_modele'] = $id_modele;
    }

    //Don't update if no fields are provided
    if (empty($fields)) {
        return false;
    }

    $query = "UPDATE panneau SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $db->prepare($query);

    //Binding params
    foreach ($params as $key => $value) {
        $stmt->bindParam($key, $value);
    }
    
    if ($stmt->execute()) {
        return getPanneauParId($db, $id);
    }
    return false;
}

// Delete a panel by its ID
function deletePanneau($db, $id) {
    try {
        $stmt = $db->prepare("DELETE FROM panneau WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (\Exception $e) {
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

//////////////////////Marque Panneau/////////////////////////////////

// Get a panel brand by its ID
function getMarquePanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM marque_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get a brand ID by its name
function getIdMarquePanneauParMarque($db, $marque){
    $stmt = $db->prepare("SELECT id FROM marque_panneau WHERE Panneaux_marque = :marque");
    $stmt->bindParam(':marque', $marque);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all panel brands
function getMarquesPanneaux($db) {
    $stmt = $db->query("SELECT * FROM marque_panneau");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create a new panel brand if it doesn't already exist
function createMarquePanneau($db, $marque) {
    if(!getIdMarquePanneauParMarque($db, $marque)){
        $stmt = $db->prepare("INSERT INTO marque_panneau (Panneaux_marque) VALUES (:marque)");
        $stmt->bindParam(':marque', $marque);
        if($stmt->execute()){
            return $db->lastInsertId();
        } else{
            return false;
        }
        
    }
}

// Update a panel brand's name
function updateMarquePanneau($db, $id, $marque){
    $stmt = $db->prepare("UPDATE marque_panneau SET Panneaux_marque = :marque WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':marque', $marque);
    if($stmt->execute()){
        return ['id' => $id, 'marque' => $marque];
    } else{
        return false;
    }
}

// Delete a panel brand by its ID
function deleteMarquePanneau($db, $id) {
    try {
        $stmt = $db->prepare("DELETE FROM marque_panneau WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (\Exception $e) {
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

//////////////////////Modele Panneau/////////////////////////////////

// Get a panel model by its ID
function getModelePanneauParId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM modele_panneau WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all panel models
function getModelesPanneaux($db) {
    $stmt = $db->query("SELECT * FROM modele_panneau");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create a new panel model if it doesn't already exist
function createModelePanneau($db, $modele) {
    if(!getIdModelePanneauParModele($db, $modele)){
        $stmt = $db->prepare("INSERT INTO modele_panneau (Panneaux_modele) VALUES (:modele)");
        $stmt->bindParam(':modele', $modele);
        if($stmt->execute()){
            return $db->lastInsertId();
        } else{
            return false;
        }
        
    }
}

// Get a model ID by its name
function getIdModelePanneauParModele($db, $modele){
    $stmt = $db->prepare("SELECT id FROM modele_panneau WHERE Panneaux_modele = :modele");
    $stmt->bindParam(':modele', $modele);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update a panel model's name
function updateModelePanneau($db, $id, $modele){
    $stmt = $db->prepare("UPDATE modele_panneau SET Panneaux_modele = :modele WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':modele', $modele);
    if($stmt->execute()){
        return ['id' => $id, 'modele' => $modele];
    } else{
        return false;
    }
}

// Delete a panel model by its ID
function deleteModelePanneau($db, $id) {
    try{
        $stmt = $db->prepare("DELETE FROM modele_panneau WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return true;
    } catch (\Exception $e) {
        return false;
    } catch (PDOException $e) {
        return false;
    }
}
