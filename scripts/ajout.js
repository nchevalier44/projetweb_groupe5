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

// Add event listeners to the input fields to check if all required fields are filled
document.querySelectorAll("input, select").forEach((input) => {
  input.addEventListener("input", disableSubmitButton);
});

//When the DOM is fully loaded, initialize the form
document.addEventListener("DOMContentLoaded", () => {
    setupVilleAutocomplete();
});


document.getElementById("searchForm").addEventListener("submit", function (e) {
  e.preventDefault();
  submitForm();
});

async function submitForm(){

    const dataToSend = {
        Iddoc: document.getElementById("iddoc").value,
        Mois_installation: document.getElementById("date-installation").value.split("-")[1],
        An_installation: document.getElementById("date-installation").value.split("-")[0],
        Nb_panneaux: document.getElementById("nb-panneaux").value,
        Panneaux_modele: document.getElementById("modele-panneaux").value,
        Panneaux_marque: document.getElementById("marque-panneaux").value,
        Nb_onduleurs: document.getElementById("nb-onduleurs").value,
        Onduleur_modele: document.getElementById("modele-onduleurs").value,
        Onduleur_marque: document.getElementById("marque-onduleurs").value,
        Puissance_crete: document.getElementById("puissance-cretes").value,
        Pente: document.getElementById("pente").value,
        Orientation: document.getElementById("orientation").value,
        Surface: document.getElementById("surface").value,
        Production_pvgis: document.getElementById("production-pvgis").value,
        Pente_optimum: document.getElementById("pente-optimum").value,
        Orientation_opti: document.getElementById("orientation-optimum").value,
        Lat: document.getElementById("latitude").value,
        Lon: document.getElementById("longitude").value,
        Installateur: document.getElementById("installateur").value,
        Nom_standard: document.getElementById("ville").value,
      };
    


    //For each of these blocks, we will first check if the marque/model exists, if not we will create it and get the ID
  //Function located in utils.js

  let id_panneau = await getIdFromExistingOrNewPanneau(dataToSend.Panneaux_marque, dataToSend.Panneaux_modele);
  if (id_panneau === null) {
    console.error("Erreur lors de la récupération de l'ID du panneau");
    return;
  }
  id_panneau = parseInt(id_panneau);

  let id_onduleur = await getIdFromExistingOrNewOnduleur(dataToSend.Onduleur_marque, dataToSend.Onduleur_modele);
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


  //We first get the insee code of the city, if it doesn't exist we alert the user
  let code_insee = await fetch(
    `../api/solar_manager/villes/?Nom_standard=${dataToSend.Nom_standard}`
  );
  if (!code_insee.ok) {
    if (alert("Ville non reconnue, veuillez entrer une ville valide")) {
      return;
    }
  }
  code_insee = await code_insee.json();
  code_insee = code_insee[0]['code_insee'];

  //We just create a localisation object with the data we have
  let localisation = {
    id: dataToSend.localisation_id,
    code_insee: code_insee,
    Lat: dataToSend.Lat,
    Lon: dataToSend.Lon,
  };

  //creating the new localisation
  let id_localisation = await fetch(`../api/solar_manager/localisations/`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(localisation),
  });
    if (!id_localisation.ok) {
        console.error("Erreur lors de la création de la localisation");
        return;
    }
    id_localisation = await id_localisation.json();
    id_localisation = id_localisation['id'];

    //Now we can create the new installation with all the data we have
    let installation = {
      Iddoc: parseInt(dataToSend.Iddoc),
      Mois_installation: parseInt(dataToSend.Mois_installation),
      An_installation: parseInt(dataToSend.An_installation),
      Nb_panneaux: parseInt(dataToSend.Nb_panneaux),
      Nb_onduleurs: parseInt(dataToSend.Nb_onduleurs),
      Pente: parseInt(dataToSend.Pente),
      Orientation: dataToSend.Orientation,
      Surface: parseInt(dataToSend.Surface),
      Puissance_crete: parseInt(dataToSend.Puissance_crete),
      Production_pvgis: parseInt(dataToSend.Production_pvgis),
      Pente_optimum: parseInt(dataToSend.Pente_optimum),
      Orientation_opti: dataToSend.Orientation_opti,
      id_installateur: parseInt(id_installateur),
      id_localisation: parseInt(id_localisation),
      id_panneau: parseInt(id_panneau),
      id_onduleur: parseInt(id_onduleur),
    };

  
  //Envoie la requête POST pour créer l'installation
  let response = await fetch(`../api/solar_manager/installations/`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(installation),
  });
  if (!response.ok) {
    console.error(
      "Erreur lors de la création de l'installation : " + response.statusText
    );
    return;
  }
  console.log("Installation créée avec succès");
  // Affiche un message de succès
  alert("Installation créée avec succès !");
  
    

}
