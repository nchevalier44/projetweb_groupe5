import { displayErrorMessage, fillSelect } from "./utils.js";

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("region-select");
  fillSelect("annee-installation-select");
  
  document.getElementById("value-select").innerText = document.getElementById("nbinstal").textContent;
});


async function updateTextNbInstallation() {
  let paragraphe = document.getElementById("nb-installation-annee-region");

  let select_annee = document.getElementById("annee-installation-select");
  let select_region = document.getElementById("region-select");
  let annee = select_annee.options[select_annee.selectedIndex].text;
  let region = select_region.options[select_region.selectedIndex].text;
  
  //Clear paragraph text
  paragraphe.innerText = "Nombre d'installations";
  if(select_annee.value != ""){
    paragraphe.innerText += " en " + annee;
    
  }
  if(select_region.value != ""){
    paragraphe.innerText += " en " + region;
  }

  let response = await fetch(`../api/solar_manager/installations/?annee=${select_annee.value}&id-region=${select_region.value}`);
  if(!response.ok){
    displayErrorMessage("Erreur lors de la récupération des installations");
    return;
  }
  let installations = await response.json();
  document.getElementById("value-select").innerText = installations.length;
}

document.getElementById("annee-installation-select").addEventListener("change", updateTextNbInstallation);
document.getElementById("region-select").addEventListener("change", updateTextNbInstallation);