async function fillSelect(id) {
  let select = document.getElementById(id);

  let path = "/api/solar_manager/";
  let error_message = "";
  let default_option_message = "";

  switch (id) {
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

  /*let response = await fetch(path);
    
    if(!response.ok){
        console.error(error_message + response.statusText);
        return;
    }
    
    let datas = await response.json();*/

  // Simulated data for demonstration purposes
  let datas = [
    { id: 1, name: "Data 1" },
    { id: 2, name: "Data 2" },
    { id: 3, name: "Data 3" },
  ];

  select.innerHTML = ""; //Clear existing options

  //Add default option
  select.innerHTML += `<option value="" selected>${default_option_message}</option>`;

  for (let data of datas) {
    select.innerHTML += `<option value="${data.id}">${data.name}</option>`;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("departements-select");
  fillSelect("marques-onduleurs-select");
  fillSelect("marques-panneaux-select");
});
