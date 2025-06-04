<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil Solar Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../styles/index.css">
  <script src="../scripts/index.js" type="module"></script>



</head>

<body>

  <?php
  include_once "navbar.html";
  ?>

  <header class="d-flex align-items-center mb-4">
    <img src="../images/logo.png" alt="Logo" class="logo me-3">
    <div>
      <h1 class="m-0 fw-bold lh-1">Solar<br>Manager</h1>
    </div>
  </header>

  <section class="container intro-box mb-5">
    <p class="mb-0">
      Notre plateforme met en valeur la transition énergétique à travers les données d’installations solaires chez les particuliers.<br>
      Elle propose des outils simples pour rechercher, filtrer et analyser ces données.<br>
      Vous y trouverez des statistiques globales, des informations précises par installation et une visualisation sur carte.<br>
      L’objectif est de rendre les données accessibles et compréhensibles pour tous.<br>
      Explorez dès maintenant les milliers d’installations solaires réparties en France.
    </p>
  </section>

  <section class="container text-center mb-5">
    <h2 class="fw-bold mb-4">Statistiques</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre d’installations total</div>
          <div class="stat-value" id="nbinstal"></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre de marques <br>de panneaux solaires</div>
          <div class="stat-value" id="nbpanneau"></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre de marques d’onduleurs</div>
          <div class="stat-value" id="nbonduleur"></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre d’installateurs</div>
          <div class="stat-value" id="nbinstallateur"></div>
        </div>
      </div>
    </div>
  </section>

  <section class=" container text-center">
    <div class="row justify-content-center align-items-center g-3 mb-3">
      <div class="col-auto">
        <label for="annee-installation" class="form-label fw-semibold">Années :</label>
        <select name="annee-installation" id="annee-installation-select" class="form-select"></select>
      </div>
      <div class="col-auto">
        <label for="region" class="form-label fw-semibold">Régions :</label>
        <select name="region" id="region-select" class="form-select"></select>
      </div>
    </div>
    <p class="fw-semibold mt-3" id="nb-installation-annee-region">Nombre d’installations totales</p>
    <p class="stat-value">3500</p>
  </section>


  <?php
  include_once "footer.html";
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>