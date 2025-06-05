import { fillSelect } from "./utils.js";

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("departements-select", 20);
  fillSelect("marques-onduleurs-select", 20);
  fillSelect("marques-panneaux-select", 20);

});

document.getElementById("departements-select").addEventListener("change", searchInstallation);
document.getElementById("marques-onduleurs-select").addEventListener("change", searchInstallation);
document.getElementById("marques-panneaux-select").addEventListener("change", searchInstallation);



async function searchInstallation(){
  let departements_id = Array.from(document.getElementById("departements-select").selectedOptions).map(opt => opt.value);
  let marques_onduleurs_id = Array.from(document.getElementById("marques-onduleurs-select").selectedOptions).map(opt => opt.value);
  let marques_panneaux_id = Array.from(document.getElementById("marques-panneaux-select").selectedOptions).map(opt => opt.value);

  let response = await fetch(`../api/solar_manager/installations/?id-departement=${departements_id}&id-marque-onduleur=${marques_onduleurs_id}&id-marque-panneau=${marques_panneaux_id}`);
  if(!response.ok){
        console.error(error_message + response.statusText);
        return;
  }
    
  let installations = await response.json();
  displayResults(installations);
}

function displayResults(installations) {
  let container = document.getElementById("installations-list");
  container.innerHTML = ""; // Clear previous results
  

  for(let i = 0; i<99; i++){
    let installation = installations[i];
    //If month is 6 (June), we write 06
    let mois = installation.Mois_installation;
    if(installation.Mois_installation / 10 < 1) {
      mois = "0" + installation.Mois_installation
    }

    container.innerHTML += `
    <div class='rounded-pill container w-25 mb-5 pb-3 pt-3 ps-5 pe-5 container-installation'>
      <h3 class='text-center'>${installation.nom_ville} - ${mois}/${installation.An_installation}</h3>
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
