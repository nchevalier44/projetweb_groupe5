import {
  getLocalisation,
  getInstallateur,
  getPanneau,
  getOnduleur,
  setupVilleAutocomplete,
  disableSubmitButton,
  getIdFromExistingOrNewInstallateur,
  getIdFromExistingOrNewPanneau,
  getIdFromExistingOrNewOnduleur,
} from "./utils.js";

//Add event listener to the submit button
document.getElementsByClassName("submit")[0].addEventListener("click", function (e) {
    e.preventDefault();

    if (confirm("Êtes-vous sûr de vouloir modifier cette installation ?")) {
      updateInstallation();
    }
  });

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
  
  //Prefill the form fields
  document.getElementById("iddoc").value = installation.Iddoc || "";
  document.getElementById("date-installation").value = installation.An_installation + "-" + String(installation.Mois_installation).padStart(2, "0");
  document.getElementById("nb-panneaux").value = installation.Nb_panneaux || "";
  document.getElementById("modele-panneaux").value = panneau.Panneaux_modele || "";
  document.getElementById("marque-panneaux").value = panneau.Panneaux_marque || "";
  document.getElementById("nb-onduleurs").value = installation.Nb_onduleurs || "";
  document.getElementById("modele-onduleurs").value = onduleur.Onduleur_modele || "";
  document.getElementById("marque-onduleurs").value = onduleur.Onduleur_marque || "";
  document.getElementById("puissance-cretes").value = installation.Puissance_crete || "";
  document.getElementById("surface").value = installation.Surface || "";
  document.getElementById("pente").value = installation.Pente || "";
  document.getElementById("pente-optimum").value = installation.Pente_optimum || "";
  document.getElementById("orientation").value = installation.Orientation || "";
  document.getElementById("orientation-optimum").value = installation.Orientation_optimum || "";
  document.getElementById("installateur").value = installateur.Installateur || "";
  document.getElementById("production-pvgis").value = installation.Production_pvgis || "";
  document.getElementById("latitude").value = installation.Lat || "";
  document.getElementById("longitude").value = installation.Lon || "";
  document.getElementById("location-id").value = installation.id_localisation || "";

  setupVilleAutocomplete();

  document.getElementById("ville").value = installation.Nom_standard || "";

  
}

document.addEventListener("DOMContentLoaded", function () {
  fillDetails();
});

async function updateInstallation() {
  let id = document.getElementById("installation-id").value;

  let dataToSend = {
    Iddoc: document.getElementById("iddoc").value,
    Nb_panneaux: document.getElementById("nb-panneaux").value,
    Nb_onduleurs: document.getElementById("nb-onduleurs").value,
    Puissance_crete: document.getElementById("puissance-cretes").value,
    Pente: document.getElementById("pente").value,
    Orientation: document.getElementById("orientation").value,
    Surface: document.getElementById("surface").value,
    Production_pvgis: document.getElementById("production-pvgis").value,
    Pente_optimum: document.getElementById("pente-optimum").value,
    Orientation_opti: document.getElementById("orientation-optimum").value,
    Mois_installation: document.getElementById("date-installation").value.split("-")[1],
    An_installation: document.getElementById("date-installation").value.split("-")[0],
    Installateur: document.getElementById("installateur").value,
    Modele_panneau: document.getElementById("modele-panneaux").value,
    Marque_panneau: document.getElementById("marque-panneaux").value,
    Modele_onduleur: document.getElementById("modele-onduleurs").value,
    Marque_onduleur: document.getElementById("marque-onduleurs").value,
    Lat: parseFloat(document.getElementById("latitude").value),
    Lon: parseFloat(document.getElementById("longitude").value),
    Nom_standard: document.getElementById("ville").value,
    localisation_id: document.getElementById("location-id").value,
  };

  //For each of these blocks, we will first check if the marque/model exists, if not we will create it and get the ID
  //Function located in utils.js

  let id_panneau = await getIdFromExistingOrNewPanneau(dataToSend.Marque_panneau, dataToSend.Modele_panneau);
  if (id_panneau === null) {
    console.error("Erreur lors de la récupération de l'ID du panneau");
    return;
  }
  id_panneau = parseInt(id_panneau);

  let id_onduleur = await getIdFromExistingOrNewOnduleur(dataToSend.Marque_onduleur, dataToSend.Modele_onduleur);
  if (id_onduleur === null) {
    console.error("Erreur lors de la récupération de l'ID de l'onduleur");
    return;
  }
  id_onduleur = parseInt(id_onduleur);

  let id_installateur = await getIdFromExistingOrNewInstallateur(dataToSend.Installateur);
  if (id_installateur === null) {
    console.error("Erreur lors de la récupération de l'ID de l'installateur");
    return;
  }
  id_installateur = parseInt(id_installateur);

  
  //localisation
  //We first get the insee code of the city in input
  let code_insee = await fetch(`../api/solar_manager/villes/?Nom_standard=${dataToSend.Nom_standard}`)
  if(!code_insee.ok){
    if(confirm("Ville non reconnue, veuillez entrer une ville valide")){
      return;
    }
  }
  code_insee = await code_insee.json();
  code_insee = code_insee[0].code_insee;

  //We just update the localisation with the new data
  let localisation = {
    id: dataToSend.localisation_id,
    code_insee: code_insee,
    Lat: dataToSend.Lat,
    Lon: dataToSend.Lon,
  };

  let localisationResponse = await fetch(`../api/solar_manager/localisations/?id=${dataToSend.location_id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(localisation),
  });
  if (!localisationResponse.ok) {
    console.error(
      "Erreur lors de la mise à jour de la localisation : " +
        localisationResponse.statusText
    );
    return;
  }
  console.log("Localisation mise à jour avec succès");
  


  //Finally, we can update the installation with the new data

  let installation = {
    id: id,
    Iddoc: parseInt(dataToSend.Iddoc),
    Nb_panneaux: parseInt(dataToSend.Nb_panneaux),
    Nb_onduleurs: parseInt(dataToSend.Nb_onduleurs),
    Puissance_crete: parseInt(dataToSend.Puissance_crete),
    Pente: parseInt(dataToSend.Pente),
    Orientation: dataToSend.Orientation,
    Surface: parseInt(dataToSend.Surface),
    Production_pvgis: parseInt(dataToSend.Production_pvgis),
    Pente_optimum: parseInt(dataToSend.Pente_optimum),
    Orientation_optimum: dataToSend.Orientation_opti,
    Mois_installation: parseInt(dataToSend.Mois_installation),
    An_installation: parseInt(dataToSend.An_installation),
    id_panneau: parseInt(id_panneau),
    id_onduleur: parseInt(id_onduleur),
    id_installateur: parseInt(id_installateur),
    id_localisation: parseInt(localisation.id),
  
  };
  console.log(JSON.stringify(installation));
  console.log("Installation data to send:", installation);
  let installationResponse = await fetch(`../api/solar_manager/installations/`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(installation),
  });
  if (!installationResponse.ok) {
    console.error(
      "Erreur lors de la mise à jour de l'installation : " +
        installationResponse.statusText
    );
    return;

    }
  console.log("Installation mise à jour avec succès");
  alert("Installation modifiée avec succès !");

}


// Add event listeners to the input fields to check if all required fields are filled
document.querySelectorAll("input, select").forEach((input) => {
  input.addEventListener("input", disableSubmitButton);
});