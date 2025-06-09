import { displayErrorMessage, fillSelect } from "./utils.js";

const INSTALLATIONS_LIMIT_PER_PAGE = 99;

// Fill the select elements with options from the API
document.addEventListener("DOMContentLoaded", () => {
  fillSelect("departements-select", 20);
  fillSelect("marques-onduleurs-select", 20);
  fillSelect("marques-panneaux-select", 20);
  searchInstallation();
});

document.getElementById("departements-select").addEventListener("change", () => searchInstallation(99, 0));
document.getElementById("marques-onduleurs-select").addEventListener("change", () => searchInstallation(99, 0));
document.getElementById("marques-panneaux-select").addEventListener("change", () => searchInstallation(99, 0));
document.getElementById("previous-page").addEventListener("click", () => changePage(-1));
document.getElementById("next-page").addEventListener("click", () => changePage(1));

// Search installations based on selected filters
async function searchInstallation(limit=99, offset=0) {
  let departements_id = Array.from(document.getElementById("departements-select").selectedOptions).map(opt => opt.value);
  let marques_onduleurs_id = Array.from(document.getElementById("marques-onduleurs-select").selectedOptions).map(opt => opt.value);
  let marques_panneaux_id = Array.from(document.getElementById("marques-panneaux-select").selectedOptions).map(opt => opt.value);
  
  let path = `../api/solar_manager/installations/?id-departement=${departements_id}&id-marque-onduleur=${marques_onduleurs_id}&id-marque-panneau=${marques_panneaux_id}`;
  path += `&limit=${limit}`;
  path += `&offset=${offset}`;
  let response = await fetch(path);
  if(!response.ok){
    displayErrorMessage("Erreur lors de la récupération des installations");
    return;
  }
    
  let installations = await response.json();
  displayResults(installations);
}

// Display the results in the HTML
function displayResults(installations) {
  let container = document.getElementById("installations-list");
  container.innerHTML = ""; // Clear previous results

  // If no installations found, display a message
  if(installations.length === 0) {
container.innerHTML = `
  <div class="alert alert-info text-center mx-auto mt-4 " role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
    </svg>
    Aucune installation trouvée.
  </div>
`;
    return;
  }

  for(let i = 0; i < installations.length; i++){
    let installation = installations[i];
    //If month is 6 (June), we write 06
    let mois = installation.Mois_installation;
    if(installation.Mois_installation / 10 < 1) {
      mois = "0" + installation.Mois_installation
    }

    // Create a new div for each installation
    // and add it to the container
    container.innerHTML += `
    <div class='rounded-5 container w-25 mb-5 pb-3 pt-3 ps-5 pe-5 container-installation' onclick="window.location.href='details.php?id=${installation.id}'">
      <h3 class='text-center'>${installation.nom_ville} | ${mois}/${installation.An_installation}</h3>
      <ul>
        <li>Latitude : ${installation.latitude} | Longitude : ${installation.longitude}</li>
        <li>Nombres de panneaux : ${installation.Nb_panneaux}</li>
        <li>Surface : ${installation.Surface}m²</li>
        <li>Puissance crête : ${installation.Puissance_crete}</li>
      </ul>
    </div>
    `;
  }

}

// Change the current page and fetch new results
async function changePage(page){
  let current_page = document.getElementById("current-page");
  if(current_page.innerText + page > 0){
    current_page.innerText = parseInt(current_page.innerText) + page;
    await searchInstallation(INSTALLATIONS_LIMIT_PER_PAGE, parseInt(current_page.innerText) * INSTALLATIONS_LIMIT_PER_PAGE);
    if(document.getElementById("installations-list").children.length == 0){
      current_page.innerText = parseInt(current_page.innerText) - page; // Revert page change if no results
    }
  }
  
}