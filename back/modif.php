<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'une installation</title>
    <!-- Bootstrap and custom CSS includes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/modif.css">
</head>

<body>

    <?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
        header('Location: ../html/index.php');
        exit();
    }

    // Include the navigation bar
    include_once "../html/navbar.php";
    ?>
    <!-- Hidden fields for installation and location IDs -->
    <input type="hidden" id="installation-id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
    <input type="hidden" id="location-id" value="">

    <!-- Page title -->
    <h1 class="text-center mt-5">Modifier l'installation </h1>

    <!-- Installation modification form -->
    <div class="container my-5">
        <form id="searchForm" class="p-4 rounded-4">

            <label class="form-label mt-2" for="iddoc">Iddoc</label>
            <input type="number" class="form-control" id="iddoc" required>

            <label class="form-label mt-2" for="date-installation">Date d’installation</label>
            <input type="month" class="form-control" id="date-installation" required>

            <label class="form-label mt-3" for="nb-panneaux">Nombre de panneaux</label>
            <input type="number" class="form-control" id="nb-panneaux" required>

            <label class="form-label mt-3" for="modele-panneaux">Modèle des panneaux photovoltaïque</label>
            <input type="text" class="form-control" id="modele-panneaux" required>

            <label class="form-label mt-3" for="marque-panneaux">Marque des panneaux photovoltaïque</label>
            <input type="text" class="form-control" id="marque-panneaux" required>

            <label class="form-label mt-3" for="nb-onduleurs">Nombre d’onduleurs</label>
            <input type="number" class="form-control" id="nb-onduleurs" required>

            <label class="form-label mt-3" for="modele-onduleurs">Modèle des onduleurs</label>
            <input type="text" class="form-control" id="modele-onduleurs" required>

            <label class="form-label mt-3" for="marque-onduleurs">Marque des onduleurs</label>
            <input type="text" class="form-control" id="marque-onduleurs" required>

            <label class="form-label mt-3" for="puissance-cretes">Puissance crête</label>
            <input type="number" class="form-control" id="puissance-cretes" required>

            <label class="form-label mt-3" for="surface">Surface m²</label>
            <input type="number" class="form-control" id="surface" required>

            <label class="form-label mt-3" for="pente">Pente</label>
            <input type="number" class="form-control" id="pente" required>

            <label class="form-label mt-3" for="pente-optimum">Pente optimum</label>
            <input type="number" class="form-control" id="pente-optimum">

            <label class="form-label mt-3" for="orientation">Orientation</label>
            <input type="text" class="form-control" id="orientation" required>

            <label class="form-label mt-3" for="orientation-optimum">Orientation optimum</label>
            <input type="text" class="form-control" id="orientation-optimum">

            <label class="form-label mt-3" for="installateur">Installateur</label>
            <input type="text" class="form-control" id="installateur" required>

            <label class="form-label mt-3" for="production-pvgis">Production PVGIS</label>
            <input type="number" class="form-control" id="production-pvgis" required>

            <label class="form-label mt-3" for="latitude">Latitude</label>
            <input type="number" class="form-control" id="latitude" required>

            <label class="form-label mt-3" for="longitude">Longitude</label>
            <input type="number" class="form-control" id="longitude" required>

            <label class="form-label mt-3" for="ville">Ville</label>
            <input list="villes" class="form-control" id="ville" required>
            <datalist id="villes"></datalist>

            <div class="row mt-4">
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary px-5 submit">Envoyer</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Include the footer -->
    <?php
    include_once "../html/footer.html";
    ?>
    <!-- Back office and modification JS logic -->
    <script src="../scripts/back.js"></script>
    <script src="../scripts/modif.js" type="module"></script>
</body>

</html>