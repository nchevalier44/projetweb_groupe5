<?php
include_once "../html/navbar.html";
require_once '../api/solar_manage/database.php';
$db = connectDB();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'une installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/ajout.css">
</head>

<body>

<h1 class="text-center mt-5">Ajout d'une installation</h1>

<div class="container my-5">
    <form id="searchForm" method="POST" class="p-4 rounded-4" style="background-color: #48BFFF; border: 2px solid #333;">

        <div class="row row-cols-1 row-cols-md-4 g-3">
            <!-- Colonne 1 -->
            <div class="col">
                <label class="form-label">Date d’installation</label>
                <input type="date" class="form-control" name="date_installation" id="date_installation">

                <label class="form-label mt-3">Nombre de panneaux</label>
                <input type="text" class="form-control" name="nombre_panneaux" id="nombre_panneaux">

                <label class="form-label mt-3">Modèle des panneaux</label>
                <select class="form-control" name="modele_du_panneau" id="modele_du_panneau">
                    <?php
                    $sql = "SELECT id, panneaux_modele FROM modele_panneau";
                    foreach ($db->query($sql) as $row) {
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['panneaux_modele']) . '</option>';
                    }
                    ?>
                </select>

                <label class="form-label mt-3">Marque des panneaux photovoltaïque</label>
                <select class="form-control" name="marque_du_panneaux" id="marque_du_panneaux">
                    <?php
                    $sql = "SELECT id, panneaux_marque FROM marque_panneau";
                    foreach ($db->query($sql) as $row) {
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['panneaux_marque']) . '</option>';
                    }
                    ?>
                </select>

                <label class="form-label mt-3">Nombre d’onduleurs</label>
                <input type="text" class="form-control" name="nombre_onduleurs" id="nombre_onduleurs">
            </div>

            <!-- Colonne 2 -->
            <div class="col">
                <label class="form-label">Modèle des onduleurs</label>
                <select class="form-control" name="modele_du_onduleur" id="modele_du_onduleur">
                    <?php
                    $sql = "SELECT id, onduleur_modele FROM modele_onduleur";
                    foreach ($db->query($sql) as $row) {
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['onduleur_modele']) . '</option>';
                    }
                    ?>
                </select>

                <label class="form-label mt-3">Marque des onduleurs</label>
                <select class="form-control" name="marque_du_onduleur" id="marque_du_onduleur">
                    <?php
                    $sql = "SELECT id, onduleur_marque FROM marque_onduleur";
                    foreach ($db->query($sql) as $row) {
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['onduleur_marque']) . '</option>';
                    }
                    ?>
                </select>

                <label class="form-label mt-3">Puissance crête</label>
                <input type="text" class="form-control" name="puissance_crete" id="puissance_crete">

                <label class="form-label mt-3">Surface</label>
                <input type="text" class="form-control" name="surface" id="surface">

                <label class="form-label mt-3">Pente</label>
                <input type="text" class="form-control" name="pente" id="pente">
            </div>

            <!-- Colonne 3 -->
            <div class="col">
                <label class="form-label">Pente optimum</label>
                <input type="text" class="form-control" name="pente_optimum" id="pente_optimum">

                <label class="form-label mt-3">Orientation</label>
                <input type="text" class="form-control" name="orientation" id="orientation">

                <label class="form-label mt-3">Orientation optimum</label>
                <input type="text" class="form-control" name="orientation_optimum" id="orientation_optimum">

                <label class="form-label mt-3">Installateur</label>
                <input type="text" class="form-control" name="installateur" id="installateur">

                <label class="form-label mt-3">Production PVGIS</label>
                <input type="text" class="form-control" name="production_pvgis" id="production_pvgis">
                <label class="form-label">numerodoc</label>
                <input type="text" class="form-control" name="iddoc" id="iddoc">

            </div>

            <!-- Colonne 4 -->
            <div class="col">
                <label class="form-label">Latitude</label>
                <input type="text" class="form-control" name="latitude" id="latitude">

                <label class="form-label mt-3">Longitude</label>
                <input type="text" class="form-control" name="longitude" id="longitude">

                <label class="form-label mt-3">code_insee</label>
                <label class="form-label mt-3">code_insee</label>
                <select class="form-control" name="codeinsee" id="codeinsee">
                    <?php
                    $sql = "SELECT code_insee FROM localisation";
                    foreach ($db->query($sql) as $row) {
                        $code = (int) $row['code_insee']; // conversion explicite en int
                        echo '<option value="' . $code . '">' . $code . '</option>';
                    }
                    ?>
                </select>


            </div>
        </div>

        <div class="row mt-4">
            <div class="col d-flex justify-content-center">
                <button type="submit" class="btn btn-success px-5">Envoyer</button>
            </div>
        </div>

    </form>
</div>

<?php include_once "../html/footer.html"; ?>
<script src="../scripts/ajout.js"></script>
</body>

</html>
