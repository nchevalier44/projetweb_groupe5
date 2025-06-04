import { fillSelect } from "./utils.js";

function displayStatistics(data) {
  // Function to display statistics
  data = [
    { id: 1, value: "20 000" },
    { id: 2, value: "12" },
    { id: 3, value: "28" },
    { id: 4, value: "69" },
  ];

  let nbinstal = document.getElementById("nbinstal");
  let nbpanneau = document.getElementById("nbpanneau");
  let nbonduleur = document.getElementById("nbonduleur");
  let nbinstallateur = document.getElementById("nbinstallateur");
  nbinstal.innerHTML = data[0].value;
  nbpanneau.innerHTML = data[1].value;
  nbonduleur.innerHTML = data[2].value;
  nbinstallateur.innerHTML = data[3].value;
}

let data = [];
document.addEventListener("DOMContentLoaded", () => {
  fillSelect("region-select");
  fillSelect("annee-installation-select");
  displayStatistics(data);
});


function updateTextNbInstallation() {
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
  if(select_region != ""){
    paragraphe.innerText += " en " + region;
  }

  if(select_annee.value == "" && select_region.value == ""){
    paragraphe.innerText = "Nombre total d'installations";
  }
}

document.getElementById("annee-installation-select").addEventListener("change", updateTextNbInstallation);
document.getElementById("region-select").addEventListener("change", updateTextNbInstallation);