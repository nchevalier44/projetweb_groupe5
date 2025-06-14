<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link href="../styles/recherche.css" rel="stylesheet">
    <script src="../scripts/recherche.js" type="module"></script>
</head>

<body>

    <!-- Include Navbar -->
    <?php
    include_once "navbar.php";
    ?>

    <!-- Title -->
    <h1 class="text-center mt-5">Recherche d'installation</h1>

    <!-- Search Form -->
    <div class="mx-auto mt-5 mb-5 pe-5 ps-5 w-75 rounded-5 container" id="container-form">

        <form id="searchForm" class="row text-center align-items-center">
            <div class="col m-4">
                <label for="departement" class="form-label">Département</label>
                <select multiple class="form-select" name="departement" id="departements-select"></select>
            </div>

            <div class="col m-4">
                <label for="marques-onduleurs" class="form-label">Marque de l'onduleur</label>
                <select multiple class="form-select" name="marques-onduleurs" id="marques-onduleurs-select"></select>
            </div>

            <div class="col m-4">
                <label for="marques-panneaux" class="form-label">Marque du panneau</label>
                <select multiple class="form-select" name="marques-panneaux" id="marques-panneaux-select"></select>
            </div>

        </form>
    </div>

    <!-- List of installations -->
    <div id="installations-list" class="container-fluid d-flex flex-wrap"></div>

    <div id="addbutton" class="d-flex justify-content-center mb-4"></div>

    <div class="container text-center">
        <button id="previous-page" class="btn btn-primary me-2">Précédent</button>
        <button id="current-page" class="btn btn-secondary me-2">0</button>
        <button id="next-page" class="btn btn-primary">Suivant</button>
    </div>
    <br>
    
    <!-- Include Footer -->
    <?php
    include_once "footer.html";
    ?>

</body>

</html>