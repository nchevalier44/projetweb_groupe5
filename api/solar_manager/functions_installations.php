<?php

function getInformationsInstallations($db){
    $stmt = $db->query("SELECT * FROM installation");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNbInstallation($db){
    $stmt = $db->query("SELECT COUNT(*) AS nombre_installation FROM installation;");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getInformationInstallationParId($db, $id){
    $stmt = $db->prepare("SELECT * FROM installation WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 1. Nombre d’installations par année (utiliser An_installation)
function getNbInstallationParAn($db){
    $stmt = $db->query("
        SELECT An_installation AS annee, COUNT(*) AS nombre_installations
        FROM installation
        GROUP BY annee
        ORDER BY annee
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// 2. Nombre d’installations par région (region.Reg_nom)
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

// 3. Nombre d’installations par année et région (par défaut 2025 / Pays de la Loire si aucun filtre)
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

function getInstallationsFilters($db, $filters){
    $query = "
        SELECT *, v.nom_standard AS nom_ville, l.Lat AS latitude, l.Lon AS longitude FROM installation i
        JOIN onduleur o ON i.id_onduleur = o.id
        
        JOIN panneau p ON i.id_panneau = p.id

        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement ON v.id = departement.id
        JOIN region On departement.id_region = region.id

        WHERE 1=1"; //'Add WHERE 1=1' to return all installations if no filters are applied

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
    $stmt = $db->prepare($query);

    //Bind parameters for each filter
    foreach ($filters as $key => $values) {
        for($i = 0; $i < count($values); $i++){
            $stmt->bindParam(':' . str_replace('.', '_', $key) . "_" . $i, $values[$i]);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 10. Dates d’installation triées (pas de champ 'date', on peut concat Mois_installation et An_installation ?)
// Sinon on affiche juste année et mois
function getDatesInstallations($db){
    $stmt = $db->query("SELECT id, An_installation AS annee, Mois_installation AS mois FROM installation ORDER BY annee, mois");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 15. Liste des années disponibles (pour fillSelect)
function getAnneesDisponibles($db){
    $stmt = $db->query("
        SELECT DISTINCT An_installation AS annee
        FROM installation
        ORDER BY annee
    ");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
// 13. Puissance crête par installation (Puissance_crete)
function getPuissanceCreteParInstallation($db){
    $stmt = $db->query("SELECT id, Puissance_crete FROM installation ORDER BY Puissance_crete");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 12. Surface des panneaux par installation (Surface)
function getSurfacePanneauxParInstallation($db){
    $stmt = $db->query("SELECT id, Surface FROM installation ORDER BY Surface");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}