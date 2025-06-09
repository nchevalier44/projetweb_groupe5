<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche sur la carte</title>
    <!-- Bootstrap, Leaflet, and custom CSS/JS includes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/carte.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<!-- SVG symbol for info alert -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
</svg>


<body>
    <!-- Include the navigation bar -->
    <?php
    include_once "navbar.php";
    ?>

    <!-- Page header -->
    <header class="d-flex align-items-center mb-4">
        <div>
            <h1 class="m-0 fw-bold lh-1">Recherche par carte</h1>
        </div>
    </header>

    <!-- Search form for year and department -->
    <div class="mx-auto mt-5 mb-5 pe-5 ps-5 w-50 rounded-5 container" id="container-form">
        <form id="searchForm" class="row text-center">
            <div class="col m-4">
                <label for="annee-installation" class="form-label">Année d'installation</label>
                <select class="form-select" name="annee-installation" id="annee-installation-select"></select>
            </div>
            <div class="col m-4">
                <label for="departements" class="form-label">Département</label>
                <select class="form-select" name="departements" id="departements-select"></select>
            </div>
        </form>
    </div>

    <!-- Map display section -->
    <div class="map-container">
        <div id="map"></div>
    </div>
    
    <!-- Include the footer -->
    <?php
    include_once "footer.html";
    ?>
    
    <!-- Bootstrap JS and custom JS includes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/carte.js" type="module"></script>
</body>

</html>