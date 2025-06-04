<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'une installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/ajout.css">
</head>

<body>

    <?php
    include_once "../html/navbar.html";
    ?>

    <h1 class="text-center mt-5">Ajout d'une installation</h1>

    <div class="container my-5">
        <form id="searchForm" class="p-4 rounded-4" style="background-color: #48BFFF; border: 2px solid #333;">

            <div class="row row-cols-1 row-cols-md-4 g-3">

                <!-- Colonne 1 -->
                <div class="col">
                    <label class="form-label">Date d’installation</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Nombre de panneaux</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Modèle des panneaux</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Marque des panneaux photovoltaïque</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Nombre d’onduleurs</label>
                    <input type="text" class="form-control">
                </div>

                <!-- Colonne 2 -->
                <div class="col">
                    <label class="form-label">Modèle des onduleurs</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Marque des onduleurs</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Puissance crête</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Surface</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Pente</label>
                    <input type="text" class="form-control">
                </div>

                <!-- Colonne 3 -->
                <div class="col">
                    <label class="form-label">Pente optimum</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Orientation</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Orientation optimum</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Installateur</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Production PVGIS</label>
                    <input type="text" class="form-control">
                </div>

                <!-- Colonne 4 -->
                <div class="col">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Longitude</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Ville</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Département</label>
                    <input type="text" class="form-control">

                    <label class="form-label mt-3">Région</label>
                    <input type="text" class="form-control">
                </div>
            </div>

            <!-- Ligne centrée pour Code Postal et Pays -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-3">
                    <label class="form-label">Code Postal</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pays</label>
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="btn btn-success px-5">Envoyer</button>
                </div>
            </div>

        </form>
    </div>

    <?php
    include_once "../html/footer.html";
    ?>

</body>

</html>