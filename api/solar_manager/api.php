<?php
require_once 'database.php';
header('Content-Type: application/json');

$db = connectDB();
$response = [];

// 1. Nombre d’installations par année
$stmt = $db->query("
    SELECT DATE_PART('year', date) AS annee, COUNT(*) AS nombre_installations
    FROM installation
    GROUP BY annee
    ORDER BY annee
");
$response['installations_par_annee'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Nombre d’installations par région
$stmt = $db->query("
    SELECT r.nom, COUNT(*) AS nombre_installations
    FROM installation i
    JOIN localisation l ON i.id_localisation = l.id
    JOIN ville v ON l.code_insee = v.code_insee
    JOIN departement d ON v.id = d.id
    JOIN region r ON d.id_region = r.id
    GROUP BY r.nom
    ORDER BY r.nom
");
$response['installations_par_region'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Nombre d’installations par année et région (par défaut 2025 / Pays de la Loire si aucun filtre)
$region = $_GET['region'] ;
$annee = $_GET['annee'] ;

$stmt = $db->prepare("
    SELECT r.nom AS region, DATE_PART('year', i.date) AS annee, COUNT(*) AS nombre_installations
    FROM installation i
    JOIN localisation l ON i.id_localisation = l.id
    JOIN ville v ON l.code_insee = v.code_insee
    JOIN departement d ON v.id = d.id
    JOIN region r ON d.id_region = r.id
    WHERE r.nom = :region AND DATE_PART('year', i.date) = :annee
    GROUP BY r.nom, annee
    ORDER BY r.nom, annee
");
$stmt->execute([':region' => $region, ':annee' => $annee]);
$response['installations_par_annee_et_region'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Nombre d’installateurs
$stmt = $db->query("SELECT COUNT(*) AS nombre_installateurs FROM installateur");
$response['nombre_installateurs'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Nombre de marques d’onduleurs
$stmt = $db->query("SELECT COUNT(DISTINCT nom) AS nombre_marques_onduleurs FROM marque_onduleur");
$response['nombre_marques_onduleurs'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 6. Nombre de marques de panneaux
$stmt = $db->query("SELECT COUNT(DISTINCT nom) AS nombre_marques_panneaux FROM marque_panneau");
$response['nombre_marques_panneaux'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 7. Installations par marque d’onduleur
$stmt = $db->query("
    SELECT mo.nom AS marque_onduleur, COUNT(i.id) AS nombre_installations
    FROM installation i
    JOIN onduleur o ON i.id_onduleur = o.id
    JOIN marque_onduleur mo ON o.id_marque_onduleur = mo.id
    GROUP BY mo.nom
");
$response['installations_par_marque_onduleur'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 8. Installations par marque de panneau
$stmt = $db->query("
    SELECT mp.nom AS marque_panneau, COUNT(i.id) AS nombre_installations
    FROM installation i
    JOIN panneau p ON i.id_panneau = p.id
    JOIN marque_panneau mp ON p.id_marque_panneau = mp.id
    GROUP BY mp.nom
");
$response['installations_par_marque_panneau'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 9. Installations par département (filtrable)
if (!empty($_GET['departement'])) {
    $departement = $_GET['departement'];
    $stmt = $db->prepare("
        SELECT d.nom AS departement, COUNT(i.id) AS nombre_installations
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement d ON v.id = d.id
        WHERE d.nom = :departement
        GROUP BY d.nom
    ");
    $stmt->execute([':departement' => $departement]);
    $response['installations_par_departement'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 10. Dates d’installation triées
$stmt = $db->query("SELECT id, date FROM installation ORDER BY date ASC");
$response['dates_installations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 11. Nombre total de panneaux installés
$stmt = $db->query("SELECT SUM(nb_panneaux) AS total_panneaux FROM installation");
$response['total_panneaux_installes'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 12. Surface des panneaux par installation
$stmt = $db->query("SELECT id, surface FROM installation ORDER BY surface");
$response['surface_par_installation'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 13. Puissance crête par installation
$stmt = $db->query("SELECT id, puissance_crete FROM installation ORDER BY puissance_crete");
$response['puissance_crete_par_installation'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 14. Localisation des installations
$stmt = $db->query("
    SELECT i.id AS id_installation, l.latitude, l.longitude
    FROM installation i
    JOIN localisation l ON i.id_localisation = l.id
");
$response['localisation_installations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 15. Liste des années disponibles (pour fillSelect)
$stmt = $db->query("
    SELECT DISTINCT DATE_PART('year', date) AS annee
    FROM installation
    ORDER BY annee
");
$response['liste_annees'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 16. Liste des régions disponibles (pour fillSelect)
$stmt = $db->query("
    SELECT DISTINCT r.nom
    FROM region r
    JOIN departement d ON r.id = d.id_region
    JOIN ville v ON d.id = v.id
    JOIN localisation l ON v.code_insee = l.code_insee
    JOIN installation i ON i.id_localisation = l.id
    ORDER BY r.nom
");
$response['liste_regions'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


echo json_encode($response);

