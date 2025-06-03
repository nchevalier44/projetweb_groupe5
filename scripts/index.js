async function fillSelect(id) {
  let select = document.getElementById(id);
  let path = "/api/solar_manager/";
  let error = "";
  let defaultOption = "";

  switch (id) {
    case "annee":
      path += "annees";
      error = "Erreur lors de la récupération des années";
      defaultOption = "Séléctionner une année";
      break;
    case "region":
      path += "region";
      error = "Erreur lors de la récupération des regions";
      defaultOption = "Séléctionner une region";
      break;
    default:
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

  select.innerHTML = ""; //Clear

  select.innerHTML += `<option value="">${defaultOption}</option>`;
  for (let data of datas) {
    select.innerHTML += `<option value="${data.id}">${data.name}</option>`;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("region");
  fillSelect("annee");
});
