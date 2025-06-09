<?php

// Get all installation information
function getInformationsInstallations($db){
    $stmt = $db->query("SELECT * FROM installation");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the total number of installations
function getNbInstallation($db){
    $stmt = $db->query("SELECT COUNT(*) AS nombre_installation FROM installation;");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get detailed information for a single installation by its ID
function getInformationInstallationParId($db, $id){
    $query = "
        SELECT *, i.id as id, region.id as id_region, departement.id as id_departement, v.id as id_ville, v.nom_standard AS nom_ville, l.Lat AS latitude, l.Lon AS longitude 
        FROM installation i
        JOIN onduleur o ON i.id_onduleur = o.id
        JOIN panneau p ON i.id_panneau = p.id
        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement ON v.id = departement.id
        JOIN region On departement.id_region = region.id
        WHERE i.id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // fetch() au lieu de fetchAll() car on veut une seule installation

}

// Get the number of installations per year
function getNbInstallationParAn($db){
    $stmt = $db->query("
        SELECT An_installation AS annee, COUNT(*) AS nombre_installations
        FROM installation
        GROUP BY annee
        ORDER BY annee
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the number of installations per region
function getNbInstallationsParRegion($db){
    $stmt = $db->query("
        SELECT r.Reg_nom AS nom, COUNT(*) AS nombre_installations
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement d ON v.id = d.id
        JOIN region r ON d.id_region = r.id
        GROUP BY r.Reg_nom
        ORDER BY r.Reg_nom
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the number of installations per region and year
function getNbInstallationsParRegionAnnee($db,$region,$annee){

    $stmt = $db->prepare("
        SELECT r.Reg_nom AS region, i.An_installation AS annee, COUNT(*) AS nombre_installations
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement d ON v.id = d.id
        JOIN region r ON d.id_region = r.id
        WHERE r.Reg_nom = :region AND i.An_installation = :annee
        GROUP BY r.Reg_nom, i.An_installation
        ORDER BY r.Reg_nom, i.An_installation
    ");
    return $stmt->execute([':region' => $region, ':annee' => $annee]);
}

// Get installations with dynamic filters (limit, offset, and other fields)
function getInstallationsFilters($db, $filters){
    $query = "
        SELECT *,i.id as id,region.id as id_region, departement.id as id_departement, v.id as id_ville, v.nom_standard AS nom_ville, l.Lat AS latitude, l.Lon AS longitude FROM installation i
        JOIN onduleur o ON i.id_onduleur = o.id
        
        JOIN panneau p ON i.id_panneau = p.id

        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement ON v.id = departement.id
        JOIN region On departement.id_region = region.id

        WHERE 1=1"; //'Add WHERE 1=1' to return all installations if no filters are applied

    //Create end of query with order, limit and offset
    $end_query = " ORDER BY v.nom_standard";
    $limit = 0;
    $offset = 0;
    if(isset($filters['limit'])){
        $limit = $filters['limit'];
        $end_query .= " LIMIT :limit";
        unset($filters['limit']);
    }
    if(isset($filters['offset'])){
        $offset = $filters['offset'];
        $end_query .= " OFFSET :offset";
        unset($filters['offset']);
    }
        

    //Add conditions based on filters
    foreach ($filters as $key => $values) {
        $query .= " AND (";
        for($i = 0; $i < count($values); $i++){
            if($i > 0){
                $or = " OR ";
            } else {
                $or = "";
            }
            $query .= $or . "$key = :" . str_replace('.', '_', $key) . "_" . $i;
        }
        $query .= ")";
    }


    //Prepare the query
    $query .= $end_query;
    $stmt = $db->prepare($query);

    //Bind parameters for each filter
    foreach ($filters as $key => $values) {
        for($i = 0; $i < count($values); $i++){
            $stmt->bindParam(':' . str_replace('.', '_', $key) . "_" . $i, $values[$i]);
        }
    }

    if($limit != 0) $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    if($offset != 0) $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get sorted installation dates (year and month)
function getDatesInstallations($db){
    $stmt = $db->query("SELECT id, An_installation AS annee, Mois_installation AS mois FROM installation ORDER BY annee, mois");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all available years for installations
function getAnneesDisponibles($db){
    $stmt = $db->query("
        SELECT DISTINCT An_installation AS annee
        FROM installation
        ORDER BY annee
    ");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get peak power for each installation
function getPuissanceCreteParInstallation($db){
    $stmt = $db->query("SELECT id, Puissance_crete FROM installation ORDER BY Puissance_crete");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get panel surface for each installation
function getSurfacePanneauxParInstallation($db){
    $stmt = $db->query("SELECT id, Surface FROM installation ORDER BY Surface");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a new installation
function addInstallation($db, $data){
    $query = "
        INSERT INTO installation (Iddoc, An_installation, Mois_installation, id_panneau, id_onduleur, 
            Puissance_crete, Surface, Pente, Pente_optimum, Orientation, Orientation_opti, 
            id_installateur, Production_pvgis, id_localisation, Nb_panneaux, Nb_onduleurs)
        VALUES (:Iddoc, :An_installation, :Mois_installation, :id_panneau, :id_onduleur,
            :Puissance_crete, :Surface, :Pente, :Pente_optimum, :Orientation, :Orientation_opti,
            :id_installateur, :Production_pvgis, :id_localisation, :Nb_panneaux, :Nb_onduleurs)";

    //if pente_optimum and orientation_optimum are not provided, they will be set to NULL
    if (empty($data['Pente_optimum'])) {
        $data['Pente_optimum'] = null;
    }
    if (empty($data['Orientation_opti'])) {
        $data['Orientation_opti'] = null;
    }

    $stmt = $db->prepare($query);
    $stmt->bindParam(':Iddoc', $data['Iddoc']);
    $stmt->bindParam(':An_installation', $data['An_installation']);
    $stmt->bindParam(':Mois_installation', $data['Mois_installation']);
    $stmt->bindParam(':id_panneau', $data['id_panneau']);
    $stmt->bindParam(':id_onduleur', $data['id_onduleur']);
    $stmt->bindParam(':Puissance_crete', $data['Puissance_crete']);
    $stmt->bindParam(':Surface', $data['Surface']);
    $stmt->bindParam(':Pente', $data['Pente']);
    $stmt->bindParam(':Pente_optimum', $data['Pente_optimum']);
    $stmt->bindParam(':Orientation', $data['Orientation']);
    $stmt->bindParam(':Orientation_opti', $data['Orientation_opti']);
    $stmt->bindParam(':id_installateur', $data['id_installateur']);
    $stmt->bindParam(':Production_pvgis', $data['Production_pvgis']);
    $stmt->bindParam(':id_localisation', $data['id_localisation']);
    $stmt->bindParam(':Nb_panneaux', $data['Nb_panneaux']);
    $stmt->bindParam(':Nb_onduleurs', $data['Nb_onduleurs']);
    try {
        if ($stmt->execute()) {
            return ['status' => 'success', 'id' => $db->lastInsertId()];
        } else {
            return ['status' => 'error', 'message' => 'SQL Error: ' . implode(', ', $stmt->errorInfo())];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Update an existing installation
function updateInstallation($db, $data)
{
    $query = "
        UPDATE installation 
        SET Iddoc = :Iddoc, An_installation = :An_installation, Mois_installation = :Mois_installation, 
            id_panneau = :id_panneau, id_onduleur = :id_onduleur, 
            Puissance_crete = :Puissance_crete, Surface = :Surface, 
            Pente = :Pente, Pente_optimum = :Pente_optimum, 
            Orientation = :Orientation, Orientation_opti = :Orientation_opti, 
            id_installateur = :id_installateur, Production_pvgis = :Production_pvgis,
            id_localisation = :id_localisation, Nb_panneaux = :Nb_panneaux, Nb_onduleurs = :Nb_onduleurs
        WHERE id = :id";

    //if pente_optimum and orientation_optimum are not provided, they will be set to NULL
    if (empty($data['Pente_optimum'])) {
        $data['Pente_optimum'] = null;
    }
    if (empty($data['Orientation_optimum'])) {
        $data['Orientation_optimum'] = null;
    }

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':Iddoc', $data['Iddoc']);
    $stmt->bindParam(':An_installation', $data['An_installation']);
    $stmt->bindParam(':Mois_installation', $data['Mois_installation']);
    $stmt->bindParam(':id_panneau', $data['id_panneau']);
    $stmt->bindParam(':id_onduleur', $data['id_onduleur']);
    $stmt->bindParam(':Puissance_crete', $data['Puissance_crete']);
    $stmt->bindParam(':Surface', $data['Surface']);
    $stmt->bindParam(':Pente', $data['Pente']);
    $stmt->bindParam(':Pente_optimum', $data['Pente_optimum']);
    $stmt->bindParam(':Orientation', $data['Orientation']);
    $stmt->bindParam(':Orientation_opti', $data['Orientation_optimum']);  // ← Correction ici
    $stmt->bindParam(':id_installateur', $data['id_installateur']);
    $stmt->bindParam(':Production_pvgis', $data['Production_pvgis']);  // ← Correction ici
    $stmt->bindParam(':id_localisation', $data['id_localisation']);
    $stmt->bindParam(':Nb_panneaux', $data['Nb_panneaux']);
    $stmt->bindParam(':Nb_onduleurs', $data['Nb_onduleurs']);

    try {
        if ($stmt->execute()) {
            return ['status' => 'success', 'id' => $data['id']];
        } else {
            return ['status' => 'error', 'message' => 'SQL Error: ' . implode(', ', $stmt->errorInfo())];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}