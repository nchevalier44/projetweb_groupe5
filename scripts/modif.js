import {
  getLocalisation,
  getInstallateur,
  getPanneau,
  getOnduleur,
  setupVilleAutocomplete,
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

  //Take the ['0'] element of each
  location = location[0];
  panneau = panneau[0];
  onduleur = onduleur[0];
  installateur = installateur[0];

  console.log("Installation data:", installation);
  console.log("Location data:", location);
  console.log("Panneau data:", panneau);
  console.log("Onduleur data:", onduleur);
  console.log("Installateur data:", installateur);

  //Prefill the form fields
  document.getElementById("iddoc").value = installation.Iddoc || "";
  document.getElementById("date-installation").value =
    installation.An_installation +
    "-" +
    String(installation.Mois_installation).padStart(2, "0");
  document.getElementById("nb-panneaux").value = installation.Nb_panneaux || "";
  document.getElementById("modele-panneaux").value =
    panneau.Panneaux_modele || "";
  document.getElementById("marque-panneaux").value =
    panneau.Panneaux_marque || "";
  document.getElementById("nb-onduleurs").value =
    installation.Nb_onduleurs || "";
  document.getElementById("modele-onduleurs").value =
    onduleur.Onduleur_modele || "";
  document.getElementById("marque-onduleurs").value =
    onduleur.Onduleur_marque || "";
  document.getElementById("puissance-cretes").value =
    installation.Puissance_crete || "";
  document.getElementById("surface").value = installation.Surface || "";
  document.getElementById("pente").value = installation.Pente || "";
  document.getElementById("pente-optimum").value =
    installation.Pente_optimum || "";
  document.getElementById("orientation").value = installation.Orientation || "";
  document.getElementById("orientation-optimum").value =
    installation.Orientation_optimum || "";
  document.getElementById("installateur").value =
    installateur.Installateur || "";
  document.getElementById("production-pvgis").value =
    installation.Production_pvgis || "";
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

  console.log(dataToSend);
  console.log(dataToSend.Marque_panneau);
  console.log(JSON.stringify({ marque_panneau: dataToSend.Marque_panneau }));

  //For each of these blocks, we will first check if the marque/model exists, if not we will create it and get the ID

  //marque panneau
  let id_marque_panneau = await fetch(`../api/solar_manager/panneaux/marques/?Marque_panneau=${dataToSend.Marque_panneau}`);
  if (!id_marque_panneau.ok) {
    let new_id_marque_panneau = await fetch(`../api/solar_manager/panneaux/marques/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ marque_panneau: dataToSend.Marque_panneau }),
    });
    if (!new_id_marque_panneau.ok) {
      console.error(
        "Erreur lors de la création de la marque de panneau : " +
          new_id_marque_panneau.statusText
      );
      return;
    }
    id_marque_panneau = await new_id_marque_panneau.json();
  }
  else{
    id_marque_panneau = await id_marque_panneau.json();
  }
  id_marque_panneau = id_marque_panneau['id'];
  console.log("ID marque_panneau : " + id_marque_panneau);

  //modele panneau
  let id_modele_panneau = await fetch(`../api/solar_manager/panneaux/modeles/?Modele_panneau=${dataToSend.Modele_panneau}`);
  if (!id_modele_panneau.ok) {
    let new_id_modele_panneau = await fetch(`../api/solar_manager/panneaux/modeles/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ modele_panneau: dataToSend.Modele_panneau, id_marque_panneau: id_marque_panneau }),
    });
    if (!new_id_modele_panneau.ok) {
      console.error(
        "Erreur lors de la création du modèle de panneau : " +
          new_id_modele_panneau.statusText
      );
      return;
    }
    id_modele_panneau = await new_id_modele_panneau.json();
  }
  else{
    id_modele_panneau = await id_modele_panneau.json();
  }
  id_modele_panneau = id_modele_panneau['id'];

  console.log("ID modele_panneau : " + id_modele_panneau);

  //We now have the id of the modele and marque of the panel, we can check if the panel exists and create it if not

  let id_panneau = await fetch(`../api/solar_manager/panneaux/?id_modele_panneau=${id_modele_panneau}&id_marque_panneau=${id_marque_panneau}`);
  if (!id_panneau.ok) {
    console.log(
      "Aucun panneau trouvé avec le modèle et la marque spécifiés, création d'un nouveau panneau."
    );
    let new_id_panneau = await fetch(`../api/solar_manager/panneaux/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_modele_panneau: id_modele_panneau,
        id_marque_panneau: id_marque_panneau,
      }),
    });
    if (!new_id_panneau.ok) {
      console.error(
        "Erreur lors de la création du panneau : " +
          new_id_panneau.statusText
      );
      return;
    }
    id_panneau = await new_id_panneau.json();
  }
  else{
    id_panneau = await id_panneau.json();
  }
  id_panneau = id_panneau['id'];
  console.log("ID panneau : " + id_panneau);


  //marque onduleur
  let id_marque_onduleur = await fetch(`../api/solar_manager/onduleurs/marques/?Marque_onduleur=${dataToSend.Marque_onduleur}`);
  if (!id_marque_onduleur.ok) {
    console.log(
      "Aucune marque d'onduleur trouvée avec le nom spécifié, création d'une nouvelle marque."
    );
    let new_id_marque_onduleur = await fetch(`../api/solar_manager/onduleurs/marques/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ marque_onduleur: dataToSend.Marque_onduleur }),
    });
    if (!new_id_marque_onduleur.ok) {
      console.error(
        "Erreur lors de la création de la marque d'onduleur : " +
          new_id_marque_onduleur.statusText
      );
      return;
    }
    id_marque_onduleur = await new_id_marque_onduleur.json();
  }
  else{
    id_marque_onduleur = await id_marque_onduleur.json();
  }
  id_marque_onduleur = id_marque_onduleur['id'];
  console.log("ID marque_onduleur : " + id_marque_onduleur);
  //modele onduleur
  let id_modele_onduleur = await fetch(`../api/solar_manager/onduleurs/modeles/?Modele_onduleur=${dataToSend.Modele_onduleur}`);
  if (!id_modele_onduleur.ok) {
    console.log(
      "Aucun modèle d'onduleur trouvé avec le nom spécifié, création d'un nouveau modèle."
    );
    let new_id_modele_onduleur = await fetch(`../api/solar_manager/onduleurs/modeles/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ modele_onduleur: dataToSend.Modele_onduleur, id_marque_onduleur: id_marque_onduleur }),
    });
    if (!new_id_modele_onduleur.ok) {
      console.error(
        "Erreur lors de la création du modèle d'onduleur : " +
          new_id_modele_onduleur.statusText
      );
      return;
    }
    id_modele_onduleur = await new_id_modele_onduleur.json();
  }
  else{
    id_modele_onduleur = await id_modele_onduleur.json();
  }
  id_modele_onduleur = id_modele_onduleur['id'];

  console.log("ID modele_onduleur : " + id_modele_onduleur);

  //We now have the id of the modele and marque of the onduleur, we can check if the inverter exists and create it if not

  let id_onduleur = await fetch(`../api/solar_manager/onduleurs/?id_modele_onduleur=${id_modele_onduleur}&id_marque_onduleur=${id_marque_onduleur}`);
  if (!id_onduleur.ok) {
    console.log(
      "Aucun onduleur trouvé avec le modèle et la marque spécifiés, création d'un nouvel onduleur."
    );
    let new_id_onduleur = await fetch(`../api/solar_manager/onduleurs/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_modele_onduleur: id_modele_onduleur,
        id_marque_onduleur: id_marque_onduleur,
      }),
    });
    if (!new_id_onduleur.ok) {
      console.error(
        "Erreur lors de la création de l'onduleur : " +
          new_id_onduleur.statusText
      );
      return;
    }
    id_onduleur = await new_id_onduleur.json();
  }
  else{
    id_onduleur = await id_onduleur.json();
  }
  id_onduleur = id_onduleur['id']['id'];
  console.log("ID onduleur : " + id_onduleur);


  //installateur
  let id_installateur = await fetch(`../api/solar_manager/installateurs/?Installateur=${dataToSend.Installateur}`);
  if (!id_installateur.ok) {
    console.log(
      "Aucun installateur trouvé avec le nom spécifié, création d'un nouvel installateur."
    );
    let new_id_installateur = await fetch(`../api/solar_manager/installateurs/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ Installateur: dataToSend.Installateur }),
    });
    if (!new_id_installateur.ok) {
      console.error(
        "Erreur lors de la création de l'installateur : " +
          new_id_installateur.statusText
      );
      return;
    }
    id_installateur = await new_id_installateur.json();
  }
  else{
    id_installateur = await id_installateur.json();
  }
  id_installateur = id_installateur['id'];
  console.log("ID installateur : " + id_installateur);

  
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

  console.log(JSON.stringify(localisation));

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
    Iddoc: dataToSend.Iddoc,
    Nb_panneaux: dataToSend.Nb_panneaux,
    Nb_onduleurs: dataToSend.Nb_onduleurs,
    Puissance_crete: dataToSend.Puissance_crete,
    Pente: dataToSend.Pente,
    Orientation: dataToSend.Orientation,
    Surface: dataToSend.Surface,
    Production_pvgis: dataToSend.Production_pvgis,
    Pente_optimum: dataToSend.Pente_optimum,
    Orientation_optimum: dataToSend.Orientation_opti,
    Mois_installation: dataToSend.Mois_installation,
    An_installation: dataToSend.An_installation,
    id_panneau: id_panneau,
    id_onduleur: id_onduleur,
    id_installateur: id_installateur,
    id_localisation: localisation.id,
  
  };
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



