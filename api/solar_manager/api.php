<?php
require_once 'database.php';
header('Content-Type: application/json');

$db = connectDB();
$response = [];

// 1. Nombre d’installations par année (utiliser An_installation)
$stmt = $db->query("
    SELECT An_installation AS annee, COUNT(*) AS nombre_installations
    FROM installation
    GROUP BY annee
    ORDER BY annee
");
$response['installations_par_annee'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Nombre d’installations par région (region.Reg_nom)
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
$response['installations_par_region'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Nombre d’installations par année et région (par défaut 2025 / Pays de la Loire si aucun filtre)
$region = $_GET['region'] ?? 'Pays de la Loire';
$annee = $_GET['annee'] ?? 2025;

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
$stmt->execute([':region' => $region, ':annee' => $annee]);
$response['installations_par_annee_et_region'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Nombre d’installateurs
$stmt = $db->query("SELECT COUNT(*) AS nombre_installateurs FROM installateur");
$response['nombre_installateurs'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Nombre de marques d’onduleurs (marque_onduleur.Onduleur_marque)
$stmt = $db->query("SELECT COUNT(DISTINCT Onduleur_marque) AS nombre_marques_onduleurs FROM marque_onduleur");
$response['nombre_marques_onduleurs'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 6. Nombre de marques de panneaux (marque_panneau.Panneaux_marque)
$stmt = $db->query("SELECT COUNT(DISTINCT Panneaux_marque) AS nombre_marques_panneaux FROM marque_panneau");
$response['nombre_marques_panneaux'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 7. Installations par marque d’onduleur
$stmt = $db->query("
    SELECT mo.Onduleur_marque AS marque_onduleur, COUNT(i.id) AS nombre_installations
    FROM installation i
    JOIN onduleur o ON i.id_onduleur = o.id
    JOIN marque_onduleur mo ON o.id_marque_onduleur = mo.id
    GROUP BY mo.Onduleur_marque
");
$response['installations_par_marque_onduleur'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 8. Installations par marque de panneau
$stmt = $db->query("
    SELECT mp.Panneaux_marque AS marque_panneau, COUNT(i.id) AS nombre_installations
    FROM installation i
    JOIN panneau p ON i.id_panneau = p.id
    JOIN marque_panneau mp ON p.id_marque_panneau = mp.id
    GROUP BY mp.Panneaux_marque
");
$response['installations_par_marque_panneau'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 9. Installations par département (filtrable)
if (!empty($_GET['departement'])) {
    $departement = $_GET['departement'];
    $stmt = $db->prepare("
        SELECT d.Dep_nom AS departement, COUNT(i.id) AS nombre_installations
        FROM installation i
        JOIN localisation l ON i.id_localisation = l.id
        JOIN ville v ON l.code_insee = v.code_insee
        JOIN departement d ON v.id = d.id
        WHERE d.Dep_nom = :departement
        GROUP BY d.Dep_nom
    ");
    $stmt->execute([':departement' => $departement]);
    $response['installations_par_departement'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 10. Dates d’installation triées (pas de champ 'date', on peut concat Mois_installation et An_installation ?)
// Sinon on affiche juste année et mois
$stmt = $db->query("SELECT id, An_installation AS annee, Mois_installation AS mois FROM installation ORDER BY annee, mois");
$response['dates_installations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 11. Nombre total de panneaux installés
$stmt = $db->query("SELECT SUM(Nb_panneaux) AS total_panneaux FROM installation");
$response['total_panneaux_installes'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 12. Surface des panneaux par installation (Surface)
$stmt = $db->query("SELECT id, Surface FROM installation ORDER BY Surface");
$response['surface_par_installation'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 13. Puissance crête par installation (Puissance_crete)
$stmt = $db->query("SELECT id, Puissance_crete FROM installation ORDER BY Puissance_crete");
$response['puissance_crete_par_installation'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 14. Localisation des installations (Lat, Lon)
$stmt = $db->query("
    SELECT i.id AS id_installation, l.Lat AS latitude, l.Lon AS longitude
    FROM installation i
    JOIN localisation l ON i.id_localisation = l.id
");
$response['localisation_installations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 15. Liste des années disponibles (pour fillSelect)
$stmt = $db->query("
    SELECT DISTINCT An_installation AS annee
    FROM installation
    ORDER BY annee
");
$response['liste_annees'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 16. Liste des régions disponibles (pour fillSelect)
$stmt = $db->query("
    SELECT DISTINCT r.Reg_nom
    FROM region r
    JOIN departement d ON r.id = d.id_region
    JOIN ville v ON d.id = v.id
    JOIN localisation l ON v.code_insee = l.code_insee
    JOIN installation i ON i.id_localisation = l.id
    ORDER BY r.Reg_nom
");
$response['liste_regions'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


//information d'une installation sans filtre
$stmt = $db->query("
    SELECT * FROM installation;
");
$response['information installation'] = $stmt->fetchAll(PDO::FETCH_COLUMN);



echo json_encode($response);
