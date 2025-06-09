<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../styles/navbar.css">

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img id="logo" src="../images/logo.png" alt="Logo">Solar<br>Manager
      <span class="navbar-separator"></span>
    </a>

    <!-- Burger button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav w-100 justify-content-lg-evenly mb-2 mb-lg-0">
        <li class="nav-item text-center">
          <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
        </li>
        <li class="nav-item text-center">
          <a class="nav-link active" aria-current="page" href="recherche.php">Recherche</a>
        </li>
        <li class="nav-item text-center">
          <a class="nav-link active" aria-current="page" href="carte.php">Carte</a>
        </li>
        <li class="nav-item text-center log">
          <button class="user-icon-btn btn" data-bs-toggle="modal" data-bs-target="#loginModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
              <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
            </svg>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- Modal login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                      <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                  </svg>
                  Connexion
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="login-error-message" class="alert alert-danger d-none" role="alert">Identifiant ou mot de passe incorrect.</div>
              <form id="loginForm" action="../back/login.php" method="POST">
                  <div class="mb-3">
                      <label for="username" class="form-label">Identifiant</label>
                      <input type="text" class="form-control" id="username" name="username" required placeholder="Votre identifiant">
                  </div>
                  <div class="mb-4">
                      <label for="password" class="form-label">Mot de passe</label>
                      <input type="password" class="form-control" id="password" name="password" required placeholder="Votre mot de passe">
                  </div>
                  <div class="d-grid gap-2">
                      <button type="submit" class="btn btn-primary">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right me-2" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                              <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                          </svg>
                          Se connecter
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- Error div --> 
<div id="error-message" class="alert alert-danger d-none ms-auto me-auto mt-5" role="alert">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16" class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
  <span id="error-message-text"></span>
</div>

<!-- Script to show if there is a login error -->
<?php
  if(isset($_GET['login-error']) && $_GET['login-error'] == '1') {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                let loginModal = new bootstrap.Modal(document.getElementById("loginModal"));
                loginModal.show();
                let errorDiv = document.getElementById("login-error-message");
                if(errorDiv) {
                  errorDiv.classList.remove("d-none");
                }
            });
          </script>';
  }
?>