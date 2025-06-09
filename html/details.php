<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Détails</title>
    <!-- Bootstrap, Leaflet, and custom CSS/JS includes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link href="../styles/details.css" rel="stylesheet">
    <script src="../scripts/details.js" type="module"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
    <!-- Include the navigation bar -->
    <?php include_once "navbar.php"; ?>

    <!-- Title with installation number from URL -->
    <h1 class="text-center mt-5">Détails de l'installation n°<?php echo htmlspecialchars($_GET['id']); ?></h1>

    <!-- Hidden input to store installation id for JS -->
    <input type="hidden" id="installation-id" value="<?php echo htmlspecialchars($_GET['id']); ?>">

    <!-- Main details section -->
    <div class="row">
        <section class="col intro-box m-5">
            <h2>Informations sur l'installation</h2>
            <ul id="installation-info"></ul>

            <br>

            <h2>Localisation de l'installation</h2>
            <ul id="location-info"></ul>

            <br>

            <h2>Informations sur les panneaux</h2>
            <ul id="panneau-info"></ul>

            <br>

            <h2>Informations sur les onduleurs</h2>
            <ul id="onduleur-info"></ul>

        </section>
        <!-- Map display section -->
        <div class="col m-5 map-container">
            <div id="map"></div>
        </div>
    </div>

    <!-- Modification and deletion buttons -->
    <div id="modifbutton" class="d-flex justify-content-center mb-4"></div>
    <div id="deletebutton" class="d-flex justify-content-center mb-4"></div>

    <!-- Back button -->
    <div class="d-flex justify-content-center mb-4">
        <button type="button" class="btn btn-secondary" onclick="window.history.back()">Retour</button>
    </div>
    
    <!-- Include the footer -->
    <?php include_once "footer.html"; ?>

</body>

</html>