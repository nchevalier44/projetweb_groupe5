//Fill a select element with options fetched from an API endpoint

export async function fillSelect(id) {
  let select = document.getElementById(id);
  let path = "/api/solar_manager/";
  let error_message = "";
  let default_option_message = "";

  switch (id) {
    //Index page
    case "annee-installation-select":
      path += "annees";
      error_message = "Erreur lors de la récupération des années";
      default_option_message = "Sélectionner une année";
      break;
    case "region-select":
      path += "region";
      error_message = "Erreur lors de la récupération des regions";
      default_option_message = "Sélectionner une region";
      break;

    //Recherche page
    case "departements-select":
      path += "departements";
      error_message = "Erreur lors de la récupération des départements : ";
      default_option_message = "Choisissez un département";
      break;
    case "marques-onduleurs-select":
      path += "marques_onduleurs";
      error_message =
        "Erreur lors de la récupération des marques d'onduleurs : ";
      default_option_message = "Choisissez une marque d'onduleur";
      break;
    case "marques-panneaux-select":
      path += "marques_panneaux";
      error_message =
        "Erreur lors de la récupération des marques de panneaux : ";
      default_option_message = "Choisissez une marque de panneau";
      break;
  }

  /*
  let response = await fetch(path);
  if(!response.ok){
        console.error(error_message + response.statusText);
        return;
    }
    
    let datas = await response.json();*/

  let datas = [
    { id: 1, name: "Data 1" },
    { id: 2, name: "Data 2" },
    { id: 3, name: "Data 3" },
  ];

  //Clear existing options
  select.innerHTML = "";

  //Add default option
  select.innerHTML += `<option value="">${default_option_message}</option>`;

  for (let data of datas) {
    select.innerHTML += `<option value="${data.id}">${data.name}</option>`;
  }
}
