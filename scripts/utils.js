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
      value = "Reg_code";

      break;

    //Recherche page
    case "departements-select":
      path += "departements/";
      error_message = "Erreur lors de la récupération des départements : ";
      default_option_message = "Choisissez un département";
      value = "Dep_code";
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



export async function fillCityDepReg() {
  //Fill the select elements for departments and regions

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


export async function setupVilleAutocomplete() {
  //As there is too many cities, we will use a datalist to autocomplete the city input and limit the results to 50
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