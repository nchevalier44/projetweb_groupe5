<?php
require_once 'database.php';
header('Content-Type: application/json');

$db = connectDB();
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Aucune donnée reçue']);
    exit;
}

function insertInstallation(PDO $db, array $data)
{
    $requiredFields = [
        'Iddoc', 'Nb_panneaux', 'Nb_onduleurs', 'Puissance_crete', 'Pente', 'Orientation',
        'Surface', 'Production_pvgis', 'Pente_optimum', 'Orientation_opti', 'Mois_installation',
        'An_installation','Code_insee', 'Lat', 'Lon', 'Installeur',
        'Panneaux_marque', 'Panneaux_modele', 'id_marque_panneau', 'id_modele_panneau',
        'Onduleur_marque', 'Onduleur_modele', 'id_marque_onduleur', 'id_modele_onduleur'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            return ['error' => "Le champ '$field' est obligatoire."];
        }
    }

    try {

        //localisaiton
        $stmt = $db->prepare("INSERT INTO localisation (lat, lon, code_insee) VALUES (:Lat, :Lon, :code_insee)");
        $stmt->execute([
            ':Lat' => $data['Lat'],
            ':Lon' => $data['Lon'],
            ':code_insee' => $data['Code_insee']
        ]);


        $stmt = $db->prepare("SELECT id FROM localisation WHERE Lat = :Lat AND Lon = :Lon");
        $stmt->execute([
            ':Lat' => $data['Lat'],
            ':Lon' => $data['Lon']
        ]);
        $id_localisation = $stmt->fetchColumn();

        // Installateur
        $stmt = $db->prepare("INSERT INTO installateur (Installateur) VALUES (:nom) ");
        $stmt->execute([':nom' => $data['Installeur']]);
        $stmt = $db->prepare("SELECT id FROM installateur WHERE installateur = :nom");//change le car erreur
        $stmt->execute([':nom' => $data['Installeur']]);
        $id_installateur = $stmt->fetchColumn();

        // Marque et modèle panneau
        $stmt = $db->prepare("INSERT INTO marque_panneau (panneaux_marque) VALUES (:nom) ");
        $stmt->execute([':nom' => $data['Panneaux_marque']]);
        $stmt = $db->prepare("INSERT INTO modele_panneau (panneaux_modele) VALUES (:nom) ");
        $stmt->execute([':nom' => $data['Panneaux_modele']]);
        $stmt = $db->prepare("INSERT INTO panneau (id_marque_panneau, id_modele_panneau) VALUES (:id_marque, :id_modele) ");
        $stmt->execute([
            ':id_marque' => $data['id_marque_panneau'],
            ':id_modele' => $data['id_modele_panneau']
        ]);
        $stmt = $db->prepare("SELECT id FROM panneau WHERE id_marque_panneau = :id_marque AND id_modele_panneau = :id_modele");
        $stmt->execute([
            ':id_marque' => $data['id_marque_panneau'],
            ':id_modele' => $data['id_modele_panneau']
        ]);
        $id_panneau = $stmt->fetchColumn();

        // Marque et modèle onduleur
        $stmt = $db->prepare("INSERT INTO marque_onduleur (onduleur_marque) VALUES (:nom) ");
        $stmt->execute([':nom' => $data['Onduleur_marque']]);
        $stmt = $db->prepare("INSERT INTO modele_onduleur (onduleur_modele) VALUES (:nom) ");
        $stmt->execute([':nom' => $data['Onduleur_modele']]);
        $stmt = $db->prepare("INSERT INTO onduleur (id_marque_onduleur, id_modele_onduleur) VALUES (:id_marque, :id_modele) ");
        $stmt->execute([
            ':id_marque' => $data['id_marque_onduleur'],
            ':id_modele' => $data['id_modele_onduleur']
        ]);
        $stmt = $db->prepare("SELECT id FROM onduleur WHERE id_marque_onduleur = :id_marque AND id_modele_onduleur = :id_modele");
        $stmt->execute([
            ':id_marque' => $data['id_marque_onduleur'],
            ':id_modele' => $data['id_modele_onduleur']
        ]);
        $id_onduleur = $stmt->fetchColumn();

        // Insertion finale dans installation
        $stmt = $db->prepare("INSERT INTO installation (
            Iddoc, nb_panneaux, nb_onduleurs, puissance_crete, pente, orientation, surface,
            production_pvgis, pente_optimum, orientation_opti, mois_installation, an_installation,
            id_installateur, id_onduleur, id_panneau, id_localisation
        ) VALUES (
            :Iddoc, :Nb_panneaux, :Nb_onduleurs, :Puissance_crete, :Pente, :Orientation, :Surface,
            :Production_pvgis, :Pente_optimum, :Orientation_opti, :Mois_installation, :An_installation,
            :id_installateur, :id_onduleur, :id_panneau, :id_localisation
        )");

        $stmt->execute([
            ':Iddoc'             => $data['Iddoc'],
            ':Nb_panneaux'       => $data['Nb_panneaux'],
            ':Nb_onduleurs'      => $data['Nb_onduleurs'],
            ':Puissance_crete'   => $data['Puissance_crete'],
            ':Pente'             => $data['Pente'],
            ':Orientation'       => $data['Orientation'],
            ':Surface'           => $data['Surface'],
            ':Production_pvgis'  => $data['Production_pvgis'],
            ':Pente_optimum'     => $data['Pente_optimum'],
            ':Orientation_opti'  => $data['Orientation_opti'],
            ':Mois_installation' => $data['Mois_installation'],
            ':An_installation'   => $data['An_installation'],
            ':id_installateur'   => $id_installateur,
            ':id_onduleur'       => $id_onduleur,
            ':id_panneau'        => $id_panneau,
            ':id_localisation'   => $id_localisation
        ]);

        return ['success' => 'Installation ajoutée avec succès'];

    } catch (PDOException $e) {
        return ['error' => 'Erreur base de données : ' . $e->getMessage()];
    }
}

echo json_encode(insertInstallation($db, $data));

function updateData(PDO $db, array $data){
    $sql = "
        UPDATE installateur
        SET Installateur = :installateur_value
        WHERE id = :installateur_id;

        UPDATE panneau
        SET id_marque_panneau = :id_marque,
            id_modele_panneau = :id_modele
        WHERE id = :panneau_id;

        UPDATE onduleur
        SET id_marque_onduleur = :id_marque_onduleur,
            id_modele_onduleur = :id_modele_onduleur
        WHERE id = :onduleur_id;

        UPDATE modele_onduleur
        SET Onduleur_modele = :modele_onduleur_name
        WHERE id = :modele_onduleur_id;

        UPDATE modele_panneau
        SET Panneaux_modele = :modele_panneau_name
        WHERE id = :modele_panneau_id;

        UPDATE marque_onduleur
        SET Onduleur_marque = :marque_onduleur_name
        WHERE id = :marque_onduleur_id;

        UPDATE marque_panneau
        SET Panneaux_marque = :marque_panneau_name
        WHERE id = :marque_panneau_id;

        UPDATE pays
        SET Country = :country_name
        WHERE id = :pays_id;

        UPDATE region
        SET Reg_code = :reg_code,
            Reg_nom = :reg_nom,
            id_pays = :id_pays
        WHERE id = :region_id;

        UPDATE departement
        SET Dep_code = :dep_code,
            Dep_nom = :dep_nom,
            id_region = :id_region
        WHERE id = :departement_id;

        UPDATE ville
        SET Nom_standard = :nom_standard,
            Population = :population,
            Code_postal = :code_postal,
            id = :departement_id
        WHERE code_insee = :code_insee;

        UPDATE localisation
        SET Lat = :lat,
            Lon = :lon,
            code_insee = :code_insee
        WHERE id = :localisation_id;

        UPDATE installation
        SET Iddoc = :iddoc,
            Nb_panneaux = :nb_panneaux,
            Nb_onduleurs = :nb_onduleurs,
            Puissance_crete = :puissance_crete,
            Pente = :pente,
            Orientation = :orientation,
            Surface = :surface,
            Production_pvgis = :production_pvgis,
            Pente_optimum = :pente_optimum,
            Orientation_opti = :orientation_opti,
            Mois_installation = :mois_installation,
            An_installation = :an_installation,
            id_installateur = :id_installateur,
            id_onduleur = :id_onduleur,
            id_panneau = :id_panneau,
            id_localisation = :id_localisation
        WHERE id = :installation_id;
    ";

    try {
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':installateur_value', $data['installateur_value']);
        $stmt->bindValue(':installateur_id', $data['installateur_id'], PDO::PARAM_INT);

        $stmt->bindValue(':id_marque', $data['id_marque'], PDO::PARAM_INT);
        $stmt->bindValue(':id_modele', $data['id_modele'], PDO::PARAM_INT);
        $stmt->bindValue(':panneau_id', $data['panneau_id'], PDO::PARAM_INT);

        $stmt->bindValue(':id_marque_onduleur', $data['id_marque_onduleur'], PDO::PARAM_INT);
        $stmt->bindValue(':id_modele_onduleur', $data['id_modele_onduleur'], PDO::PARAM_INT);
        $stmt->bindValue(':onduleur_id', $data['onduleur_id'], PDO::PARAM_INT);

        $stmt->bindValue(':modele_onduleur_name', $data['modele_onduleur_name']);
        $stmt->bindValue(':modele_onduleur_id', $data['modele_onduleur_id'], PDO::PARAM_INT);

        $stmt->bindValue(':modele_panneau_name', $data['modele_panneau_name']);
        $stmt->bindValue(':modele_panneau_id', $data['modele_panneau_id'], PDO::PARAM_INT);

        $stmt->bindValue(':marque_onduleur_name', $data['marque_onduleur_name']);
        $stmt->bindValue(':marque_onduleur_id', $data['marque_onduleur_id'], PDO::PARAM_INT);

        $stmt->bindValue(':marque_panneau_name', $data['marque_panneau_name']);
        $stmt->bindValue(':marque_panneau_id', $data['marque_panneau_id'], PDO::PARAM_INT);

        $stmt->bindValue(':country_name', $data['country_name']);
        $stmt->bindValue(':pays_id', $data['pays_id'], PDO::PARAM_INT);

        $stmt->bindValue(':reg_code', $data['reg_code']);
        $stmt->bindValue(':reg_nom', $data['reg_nom']);
        $stmt->bindValue(':id_pays', $data['id_pays'], PDO::PARAM_INT);
        $stmt->bindValue(':region_id', $data['region_id'], PDO::PARAM_INT);

        $stmt->bindValue(':dep_code', $data['dep_code']);
        $stmt->bindValue(':dep_nom', $data['dep_nom']);
        $stmt->bindValue(':id_region', $data['id_region'], PDO::PARAM_INT);
        $stmt->bindValue(':departement_id', $data['departement_id'], PDO::PARAM_INT);

        $stmt->bindValue(':nom_standard', $data['nom_standard']);
        $stmt->bindValue(':population', $data['population'], PDO::PARAM_INT);
        $stmt->bindValue(':code_postal', $data['code_postal']);
        $stmt->bindValue(':departement_id', $data['departement_id'], PDO::PARAM_INT);
        $stmt->bindValue(':code_insee', $data['code_insee']);

        $stmt->bindValue(':lat', $data['lat']);
        $stmt->bindValue(':lon', $data['lon']);
        $stmt->bindValue(':code_insee', $data['code_insee']);
        $stmt->bindValue(':localisation_id', $data['localisation_id'], PDO::PARAM_INT);

        $stmt->bindValue(':iddoc', $data['iddoc']);
        $stmt->bindValue(':nb_panneaux', $data['nb_panneaux'], PDO::PARAM_INT);
        $stmt->bindValue(':nb_onduleurs', $data['nb_onduleurs'], PDO::PARAM_INT);
        $stmt->bindValue(':puissance_crete', $data['puissance_crete']);
        $stmt->bindValue(':pente', $data['pente']);
        $stmt->bindValue(':orientation', $data['orientation']);
        $stmt->bindValue(':surface', $data['surface']);
        $stmt->bindValue(':production_pvgis', $data['production_pvgis']);
        $stmt->bindValue(':pente_optimum', $data['pente_optimum']);
        $stmt->bindValue(':orientation_opti', $data['orientation_opti']);
        $stmt->bindValue(':mois_installation', $data['mois_installation'], PDO::PARAM_INT);
        $stmt->bindValue(':an_installation', $data['an_installation'], PDO::PARAM_INT);
        $stmt->bindValue(':id_installateur', $data['id_installateur'], PDO::PARAM_INT);
        $stmt->bindValue(':id_onduleur', $data['id_onduleur'], PDO::PARAM_INT);
        $stmt->bindValue(':id_panneau', $data['id_panneau'], PDO::PARAM_INT);
        $stmt->bindValue(':id_localisation', $data['id_localisation'], PDO::PARAM_INT);
        $stmt->bindValue(':installation_id', $data['installation_id'], PDO::PARAM_INT);

        $stmt->execute();

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
