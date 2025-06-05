import { fillSelect } from "./utils.js";


let data = [];
document.addEventListener("DOMContentLoaded", () => {
  fillSelect("region-select");
  fillSelect("annee-installation-select");
  getNbInstallation();
});


async function  updateTextNbInstallation() {
  let paragraphe = document.getElementById("nb-installation-annee-region");
  let value = document.getElementById("value-select").innerHTML;

  let select_annee = document.getElementById("annee-installation-select");
  let select_region = document.getElementById("region-select");
  let annee = select_annee.options[select_annee.selectedIndex].text;
  let region = select_region.options[select_region.selectedIndex].text;
  
  //Clear paragraph text
  paragraphe.innerText = "Nombre d'installations";
  if(select_annee.value != ""){
    paragraphe.innerText += " en " + annee;
    let response = await fetch("../api/solar_manager/onduleurs/");
    if (!response.ok) {
      console.error(error_message + response.statusText);
      return;
    }
  }
  if(select_region != ""){
    paragraphe.innerText += " en " + region;
  }

  if(select_annee.value == "" && select_region.value == ""){
    paragraphe.innerText = "Nombre d'installations totales";
    getNbInstallation();
  }
}

document.getElementById("annee-installation-select").addEventListener("change", updateTextNbInstallation);
document.getElementById("region-select").addEventListener("change", updateTextNbInstallation);

function getNbInstallation(){
  let nbInstallaiton = document.getElementById("nbinstal").innerHTML;
  document.getElementById("value-select").innerHTML = nbInstallaiton;
}