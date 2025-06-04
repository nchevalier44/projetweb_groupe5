import { fillSelect } from "./utils.js";

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("departements-select");
  fillSelect("marques-onduleurs-select");
  fillSelect("marques-panneaux-select");

});

document.getElementById("departements-select").addEventListener("change", searchInstallation);
document.getElementById("marques-onduleurs-select").addEventListener("change", searchInstallation);
document.getElementById("marques-panneaux-select").addEventListener("change", searchInstallation);



async function searchInstallation(){
  let departement_id = document.getElementById("departements-select").value;
  let marque_onduleur_id = document.getElementById("marques-onduleurs-select").value;
  let marque_panneau_id = document.getElementById("marques-panneaux-select").value;

  let response = await fetch(`../api/solar_manager/installations?id-departement=${departement_id}&id-marque-onduleur=${marque_onduleur_id}&id-marque-panneau=${marque_panneau_id}`);
  if(!response.ok){
        console.error(error_message + response.statusText);
        return;
  }
    
  let installations = await response.json();
  displayResults(installations);
}

function displayResults(installations) {
  let container = document.getElementById("installation-list");
  container.innerHTML = ""; // Clear previous results
  
  for(let installation of installations){
    container.innerHTML += `
    <div class='rounded-pill container w-25 mb-5 pb-3 pt-3 ps-5 pe-5 container-installation'>
      <h3 class='text-center'>${installation.nom_ville} - ${installation.Mois_installation}/${installation.An_installation}</h3>
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
