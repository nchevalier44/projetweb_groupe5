// Utility functions for the solar manager application
// Functions used in multiple scripts
// exported functions for utility operations in the solar manager application in different scripts

//Fill a select element with options fetched from an API endpoint
export async function fillSelect(id, limit=-1) {
  let select = document.getElementById(id);
  let path = "../api/solar_manager/";
  let error_message = "";
  let default_option_message = "";
  let value = "";
  let text = "";

  switch (id) {
    //Index page
    case "annee-installation-select":
      path += "annees/";
      error_message = "Erreur lors de la récupération des années";
      default_option_message = "Sélectionner une année";
      text = "annee";
      value= "annee";
      break;
    case "region-select":
      path += "regions/";
      error_message = "Erreur lors de la récupération des regions";
      default_option_message = "Sélectionner une region";
      text = "Reg_nom";
      value = "id";
      break;

    //Recherche page
    case "departements-select":
      path += "departements/";
      error_message = "Erreur lors de la récupération des départements : ";
      default_option_message = "Choisissez un département";
      value = "id";
      text = "Dep_nom";
      break;
    case "marques-onduleurs-select":
      path += "onduleurs/marques/";
      error_message = "Erreur lors de la récupération des marques d'onduleurs : ";
      default_option_message = "Choisissez une marque d'onduleur";
      value = "id";
      text = "Onduleur_marque";
      break;
    case "marques-panneaux-select":
      path += "panneaux/marques/";
      error_message = "Erreur lors de la récupération des marques de panneaux : ";
      default_option_message = "Choisissez une marque de panneau";
      value = "id";
      text = "Panneaux_marque";
      break;
  }

  

  let response = await fetch(path);
  if(!response.ok){
      displayErrorMessage(error_message + response.statusText);
      return;
  }

  let datas = await response.json();

  //Clear existing options
  select.innerHTML = "";

  //Add default option
  select.innerHTML += `<option value="">${default_option_message}</option>`;

  let real_limit = datas.length;
  if (limit < datas.length && limit != -1) {
    real_limit = limit;
  }

  let random_values = [];
  let randomInt = 0;
  for (let i = 0; i < real_limit; i++) {
    //We do the random only if limit is there is a valid limit defined
    if(limit != -1 && limit < datas.length){
      randomInt = Math.floor(Math.random() * datas.length);
      while(random_values.includes(randomInt)) {
        randomInt = Math.floor(Math.random() * datas.length);
      }
      random_values.push(randomInt);
    } else{
      //No random
      randomInt = i;
    }
    select.innerHTML += `<option value="${datas[randomInt][value]}">${datas[randomInt][text]}</option>`;
  }
}

//Get localisation information by id
export async function getLocalisation(id){
    let response = await fetch(`../api/solar_manager/localisations/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations de localisation n°" + id + " : " + response.statusText);
        return;
    }
    return await response.json();
}

//Get panel information by id
export async function getPanneau(id){
    let response = await fetch(`../api/solar_manager/panneaux/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations de panneau n°" + id + " : " + response.statusText);
        return;
    }
    return await response.json();
}

//Get onduleur information by id
export async function getOnduleur(id){
    let response = await fetch(`../api/solar_manager/onduleurs/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations d'onduleur n°" + id + " : " + response.statusText);
        return;
    }
    return await response.json();
}


//Get installator information by id
export async function getInstallateur(id){
    let response = await fetch(`../api/solar_manager/installateurs/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations d'installateur n°" + id + " : " + response.statusText);
        return;
    }
    return await response.json();
}

//Display an error message in the HTML
export function displayErrorMessage(message){
    let errorDiv = document.getElementById("error-message");
    let errorMessage = document.getElementById("error-message-text");
    
    //Hide error message if one is displayed
    if (errorDiv) errorDiv.classList.add("d-none");

    if (errorMessage) {
        errorMessage.textContent = message;
        errorDiv.classList.remove("d-none");
    } else {
        console.error(message);
    }
}


//Fill the select elements for departments and regions
export async function fillCityDepReg() {

  // Fetch departments and regions from the API
  let responsedep = await fetch(`../api/solar_manager/departements`);
  if (!responsedep.ok) {
    responsedep.error(
      "Erreur lors de la récupération des informations de départements : " +
        response.statusText
    );
    return;
  }

  let departements = await responsedep.json();

  let responsereg = await fetch(`../api/solar_manager/regions`);
  if (!responsereg.ok) {
    console.error(
      "Erreur lors de la récupération des informations des regions : " +
        responsereg.statusText
    );
    return;
  }

  let regions = await responsereg.json();  
// Fill the select elements with the fetched data
  let selectDep = document.getElementById("departements");
  departements.forEach((departement) => {
    selectDep.innerHTML +=
      `<option value="` +
      departement.Dep_nom +
      `">` +
      departement.Dep_code +
      `</option>`;
  });

  let selectReg = document.getElementById("regions");
  regions.forEach((region) => {
    selectReg.innerHTML +=
      `<option value="` + region.Reg_nom + `">` + region.Reg_code + `</option>`;
  });
}

// Setup autocomplete for city input using a datalist
export async function setupVilleAutocomplete() {
  //As there is too many cities, we will use a datalist to autocomplete the city input and limit the results to 50
  //Otherwise the page will be too slow to load and might crash
  let villes = [];

  try {
    let response = await fetch(`../api/solar_manager/villes`);
    villes = await response.json();
  } catch (error) {
    console.error("Erreur chargement villes:", error);
    return;
  }

  let inputVille = document.getElementById("ville");
  let datalistVille = document.getElementById("villes");

  inputVille.addEventListener("input", function () {
    let query = this.value.toLowerCase();
    datalistVille.innerHTML = "";

    if (query.length < 2) return; // At least 2 characters to trigger search

    let filtered = villes
      .filter((ville) => ville.Nom_standard.toLowerCase().includes(query))
      .slice(0, 50); // Limit to 50 results

    filtered.forEach((ville) => {
      datalistVille.innerHTML += `<option value="${ville.Nom_standard}">${ville.code_insee}</option>`;
    });
  });
}



//function to disable the submit button while all the data is not filled except the orientation_opti and the pente_optimum
export function disableSubmitButton() {
  const submitButton = document.querySelector(".submit");
  const requiredFields = [
    "iddoc",
    "date-installation",
    "nb-panneaux",
    "modele-panneaux",
    "marque-panneaux",
    "nb-onduleurs",
    "modele-onduleurs",
    "marque-onduleurs",
    "puissance-cretes",
    "surface",
    "pente",
    "orientation",
    "installateur",
    "production-pvgis",
    "latitude",
    "longitude",
  ];

  const allFilled = requiredFields.every((field) => {
    return document.getElementById(field).value.trim() !== "";
  });

  submitButton.disabled = !allFilled;
}



//Functions to get the id of an existing or a new modele/marque, check if an according panel/onduleur exists and create it if not
export async function getIdFromExistingOrNewPanneau(Marque_panneau, Modele_panneau){
  //Function handle both marque and modele of the panel and then create a new panneau if it does not exist
  //marque panneau
  let id_marque_panneau = await fetch(
    `../api/solar_manager/panneaux/marques/?Marque_panneau=${Marque_panneau}`
  );
  if (!id_marque_panneau.ok) {
    console.log(
      "Aucune marque de panneau trouvée avec le nom spécifié, création d'une nouvelle marque."
    );
    let new_id_marque_panneau = await fetch(
      `../api/solar_manager/panneaux/marques/`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ marque_panneau: Marque_panneau }),
      }
    );
    if (!new_id_marque_panneau.ok) {
      console.error(
        "Erreur lors de la création de la marque de panneau : " +
          new_id_marque_panneau.statusText
      );
      return;
    }
    id_marque_panneau = await new_id_marque_panneau.json();
  } else {
    id_marque_panneau = await id_marque_panneau.json();
  }
  id_marque_panneau = id_marque_panneau["id"];
  console.log("ID marque_panneau : " + id_marque_panneau);

  //modele panneau
  let id_modele_panneau = await fetch(
    `../api/solar_manager/panneaux/modeles/?Modele_panneau=${Modele_panneau}`
  );
  if (!id_modele_panneau.ok) {
    console.log(
      "Aucun modèle de panneau trouvé avec le nom spécifié, création d'un nouveau modèle."
    );
    
    let new_id_modele_panneau = await fetch(
      `../api/solar_manager/panneaux/modeles/`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          modele_panneau: Modele_panneau,
          id_marque_panneau: id_marque_panneau,
        }),
      }
    );
    if (!new_id_modele_panneau.ok) {
      console.error(
        "Erreur lors de la création du modèle de panneau : " +
          new_id_modele_panneau.statusText
      );
      return;
    }
    id_modele_panneau = await new_id_modele_panneau.json();
  } else {
    id_modele_panneau = await id_modele_panneau.json();
  }
  id_modele_panneau = id_modele_panneau["id"];

  console.log("ID modele_panneau : " + id_modele_panneau);

  //We now have the id of the modele and marque of the panel, we can check if the panel exists and create it if not

  let id_panneau = await fetch(
    `../api/solar_manager/panneaux/?id_modele_panneau=${id_modele_panneau}&id_marque_panneau=${id_marque_panneau}`
  );
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
        "Erreur lors de la création du panneau : " + new_id_panneau.statusText
      );
      return;
    }
    id_panneau = await new_id_panneau.json();
  } else {
    id_panneau = await id_panneau.json();
  }
  id_panneau = id_panneau["id"];
  console.log("ID panneau : " + id_panneau);

  return id_panneau;
}

export async function getIdFromExistingOrNewOnduleur(Marque_onduleur, Modele_onduleur) {
  //marque onduleur
  let id_marque_onduleur = await fetch(`../api/solar_manager/onduleurs/marques/?Marque_onduleur=${Marque_onduleur}`);
  if (!id_marque_onduleur.ok) {
    console.log(
      "Aucune marque d'onduleur trouvée avec le nom spécifié, création d'une nouvelle marque."
    );
    let new_id_marque_onduleur = await fetch(`../api/solar_manager/onduleurs/marques/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ marque_onduleur: Marque_onduleur }),
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
  let id_modele_onduleur = await fetch(`../api/solar_manager/onduleurs/modeles/?Modele_onduleur=${Modele_onduleur}`);
  if (!id_modele_onduleur.ok) {
    console.log(
      "Aucun modèle d'onduleur trouvé avec le nom spécifié, création d'un nouveau modèle."
    );
    let new_id_modele_onduleur = await fetch(`../api/solar_manager/onduleurs/modeles/`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ modele_onduleur: Modele_onduleur}),
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
  id_onduleur = id_onduleur['id'];
  console.log("ID onduleur : " + id_onduleur);

  return id_onduleur;
}

//Installateur
export async function getIdFromExistingOrNewInstallateur(Nom_installateur) {

  let id_installateur = await fetch(`../api/solar_manager/installateurs/?Installateur=${Nom_installateur}`);
  if (!id_installateur.ok) {
    console.log(
      "Aucun installateur trouvé avec le nom spécifié, création d'un nouvel installateur."
    );
    let new_id_installateur = await fetch(
      `../api/solar_manager/installateurs/`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ Installateur: Nom_installateur }),
      }
    );
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

  return id_installateur;

}

