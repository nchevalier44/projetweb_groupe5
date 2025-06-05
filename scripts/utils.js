//Fill a select element with options fetched from an API endpoint
export async function fillSelect(id) {
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
      console.error(error_message + response.statusText);
      return;
  }

  let datas = await response.json();

  //Clear existing options
  select.innerHTML = "";

  //Add default option
  select.innerHTML += `<option value="">${default_option_message}</option>`;

  for(let i = 0; i<20; i++){
    let randomInt = Math.floor(Math.random() * (datas.length-1));
    select.innerHTML += `<option value="${datas[randomInt][value]}">${datas[randomInt][text]}</option>`;
  }
}

//Get localisation information by id
export async function getLocalisation(id){
    let response = await fetch(`/api/solar_manager/localisation/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations de localisation : " + response.statusText);
        return;
    }
    return await response.json();
}

//Get panel information by id
export async function getPanneau(id){
    let response = await fetch(`/api/solar_manager/panneau/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations de panneau : " + response.statusText);
        return;
    }
    return await response.json();
}

//Get onduleur information by id
export async function getOnduleur(id){
    let response = await fetch(`/api/solar_manager/onduleur/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations d'onduleur : " + response.statusText);
        return;
    }
    return await response.json();
}


//Get installator information by id
export async function getInstallateur(id){
    let response = await fetch(`/api/solar_manager/installateur/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations d'installateur : " + response.statusText);
        return;
    }
    return await response.json();
}
