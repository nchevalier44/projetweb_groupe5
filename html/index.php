<?php
// Include required files for database connection and functions
require_once '../api/solar_manager/database.php';
require_once '../api/solar_manager/functions_installations.php';
require_once '../api/solar_manager/functions_onduleurs.php';
require_once '../api/solar_manager/functions_panneaux.php';
require_once '../api/solar_manager/functions_installateurs.php';

// Connect to the database
$db = connectDB();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil Solar Manager</title>
  <!-- Bootstrap and custom CSS/JS includes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../styles/index.css">
  <script src="../scripts/index.js" type="module"></script>
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

  <!-- Header with logo and title -->
  <header class="d-flex align-items-center mb-4">
    <img src="../images/logo.png" alt="Logo" class="logo me-3">
    <div>
      <h1 class="m-0 fw-bold lh-1">Solar<br>Manager</h1>
    </div>
  </header>

  <!-- Show info alert if user is logged out -->
  <?php if (isset($_GET['logout'])) {
    echo '<div id="info" class="alert alert-info
   d-flex align-items-center justify-content-center mt-3 w-auto container">
   <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
      <use xlink:href="#info-fill"/>
    </svg>
    <div class="mb-0">
      Vous êtes déconnecté.
      </div>
    </div>
    <script>setTimeout(() => {document.getElementById("info").remove();}, 5000);</script>';
  } ?>

  <!-- Introduction section -->
  <section class="container intro-box mb-5">
    <p class="mb-0">
      Notre plateforme met en valeur la transition énergétique à travers les données d’installations solaires chez les particuliers.<br>
      Elle propose des outils simples pour rechercher, filtrer et analyser ces données.<br>
      Vous y trouverez des statistiques globales, des informations précises par installation et une visualisation sur carte.<br>
      L’objectif est de rendre les données accessibles et compréhensibles pour tous.<br>
      Explorez dès maintenant les milliers d’installations solaires réparties en France.
    </p>
  </section>

  <!-- Main statistics section -->
  <section class="container text-center mb-5">
    <h2 class="fw-bold mb-4">Statistiques</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre d’installations total</div>
          <div class="stat-value" id="nbinstal"><?php echo (getNbInstallation($db)['nombre_installation']); ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre de marques <br>de panneaux solaires</div>
          <div class="stat-value" id="nbpanneau"><?php echo (getNbMarquesPanneaux($db)['nombre_marques_panneaux']); ?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre de marques d’onduleurs</div>
          <div class="stat-value" id="nbonduleur"><?php echo (getNbMarquesOnduleurs($db)['nombre_marques_onduleurs']); ?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card h-100 d-flex flex-column justify-content-center">
          <div class="fw-semibold">Nombre d’installateurs</div>
          <div class="stat-value" id="nbinstallateur"><?php echo (getNbInstallateurs($db)['nombre_installateurs']); ?></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Filter section for year and region, with dynamic installation count -->
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
    <p class="stat-value" id="value-select"></p>
  </section>

  <!-- Include footer -->
  <?php
  include_once "footer.html";
  ?>

  <!-- Bootstrap JS include -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>