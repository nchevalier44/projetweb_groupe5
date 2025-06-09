import { displayErrorMessage, fillSelect } from "./utils.js";

//Content loaded when the DOM is ready
// This function fills the select elements with options from the API
document.addEventListener("DOMContentLoaded", () => {
  fillSelect("region-select");
  fillSelect("annee-installation-select");
  
  document.getElementById("value-select").innerText = document.getElementById("nbinstal").textContent;
});


//Update the text of the paragraph with the number of installations according to the selected year and regions
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

//Update the text when the select elements change
document.getElementById("annee-installation-select").addEventListener("change", updateTextNbInstallation);
document.getElementById("region-select").addEventListener("change", updateTextNbInstallation);