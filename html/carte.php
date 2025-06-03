<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche sur la carte</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
        crossorigin="anonymous" />
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/carte.css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
</head>

<body>
    <?php
    include_once "navbar.html";
    ?>


    <header class="d-flex align-items-center mb-4">
        <div>
            <h1 class="m-0 fw-bold lh-1">Recherche par carte</h1>
        </div>
    </header>

    <div class="mx-auto mt-5 mb-5 pe-5 ps-5 w-50 rounded-pill container" id="container-form">

        <form id="searchForm" class="row text-center">
            <div class="col m-4">
                <label for="annee-instal" class="form-label">Année d'installation</label>
                <select class="form-select" name="annee-instal" id="annee-instal-select"></select>
            </div>
            <div class="col m-4">
                <label for="departement" class="form-label">Département</label>
                <select class="form-select" name="departement" id="departements-select"></select>
            </div>

        </form>
    </div>

    <div id="map"></div>
    <?php
    include_once "footer.html";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../scripts/carte.js"></script>
</body>

</html>