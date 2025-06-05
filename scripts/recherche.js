import { fillSelect } from "./utils.js";

const INSTALLATIONS_LIMIT_PER_PAGE = 99;

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


async function searchInstallation(limit=99, offset=0) {
  let departements_id = Array.from(document.getElementById("departements-select").selectedOptions).map(opt => opt.value);
  let marques_onduleurs_id = Array.from(document.getElementById("marques-onduleurs-select").selectedOptions).map(opt => opt.value);
  let marques_panneaux_id = Array.from(document.getElementById("marques-panneaux-select").selectedOptions).map(opt => opt.value);
  
  let path = `../api/solar_manager/installations/?id-departement=${departements_id}&id-marque-onduleur=${marques_onduleurs_id}&id-marque-panneau=${marques_panneaux_id}`;
  path += `&limit=${limit}`;
  path += `&offset=${offset}`;
  let response = await fetch(path);
  if(!response.ok){
        console.error("Erreur lors de la récupération des installations : " + response.statusText);
        return;
  }
    
  let installations = await response.json();
  console.log
  displayResults(installations);
}

function displayResults(installations) {
  let container = document.getElementById("installations-list");
  container.innerHTML = ""; // Clear previous results

  for(let i = 0; i < installations.length; i++){
    let installation = installations[i];
    //If month is 6 (June), we write 06
    let mois = installation.Mois_installation;
    if(installation.Mois_installation / 10 < 1) {
      mois = "0" + installation.Mois_installation
    }

    container.innerHTML += `
    <div class='rounded-pill container w-25 mb-5 pb-3 pt-3 ps-5 pe-5 container-installation' onclick="window.location.href='details.php?id=${installation.id}'">
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