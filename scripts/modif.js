import {
  getLocalisation,
  getInstallateur,
  getPanneau,
  getOnduleur,
  fillCityDepReg
} from "./utils.js";

async function fillDetails() {
  let id = document.getElementById("installation-id").value;

  let response = await fetch(`../api/solar_manager/installations/?id=${id}`);
  if (!response.ok) {
    console.error(
      "Erreur lors de la récupération des informations de l'installation : " +
        response.statusText
    );
    return;
  }

  let installation = await response.json();
  let location = await getLocalisation(installation.id_localisation);
  let panneau = await getPanneau(installation.id_panneau);
  let onduleur = await getOnduleur(installation.id_onduleur);
  let installateur = await getInstallateur(installation.id_installateur);

  //Take the ['0'] element of each
  location = location[0];
  panneau = panneau[0];
  onduleur = onduleur[0];
  installateur = installateur[0];

    //Prefill the form fields
  document.getElementById("date-installation").value = installation.An_installation +"-" + String(installation.Mois_installation).padStart(2, "0");
  document.getElementById("nb-panneaux").value = installation.Nb_panneaux || "";
  document.getElementById("modele-panneaux").value = panneau.Panneaux_modele || "";
  document.getElementById("marque-panneaux").value = panneau.Panneaux_marque || "";
  document.getElementById("nb-onduleurs").value = installation.Nb_onduleurs || "";
  document.getElementById("modele-onduleurs").value = onduleur.Onduleur_modele || "";
  document.getElementById("marque-onduleurs").value =onduleur.Onduleur_marque || "";
  document.getElementById("puissance-cretes").value =installation.Puissance_crete || "";
  document.getElementById("surface").value = installation.Surface || "";
  document.getElementById("pente").value = installation.Pente || "";
  document.getElementById("pente-optimum").value = installation.Pente_optimum || "";
  document.getElementById("orientation").value = installation.Orientation || "";
  document.getElementById("orientation-optimum").value = installation.Orientation_optimum || "";
  document.getElementById("installateur").value = installateur.Installateur || "";
  document.getElementById("production-pvgis").value =installation.Production_pvgis || "";
  document.getElementById("latitude").value = installation.Lat || "";
  document.getElementById("longitude").value = installation.Lon || "";

  fillCityDepReg();

}

document.addEventListener("DOMContentLoaded", function () {
  fillDetails();
});

