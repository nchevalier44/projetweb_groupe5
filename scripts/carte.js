import { fillSelect } from "./utils.js";

var map = L.map("map").setView([47.811399, 1.950145], 6);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);



async function getAllInstallationsAndAddPin() {
  let response = await fetch("../api/solar_manager/installations");
  if (!response.ok) {
    console.error(error_message + response.statusText);
    return;
  }
  let installations = await response.json();
  //generate 50 random installations
  for(let i = 0; i < 50; i++) {
    let randomId = Math.floor(Math.random() * installations.length);
    let installation = installations[randomId];
    var marker = L.marker([installation["Lat"], installation["Lon"]]).addTo(
      map
    );
    marker.bindPopup(
      `<div class='text-center'><b>${installation["Nom_standard"]}</b><br><a href='../details.php?id=${installation["ID"]}'>Voir plus de détails</a></div>`
    );
  }

}

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("annee-installation-select");
  fillSelect("departements-select");
  getAllInstallationsAndAddPin();
});

async function updateCarte(){
  let select_annee = document.getElementById("annee-installation-select");
  let select_departement = document.getElementById("departements-select");
  let annee = select_annee.options[select_annee.selectedIndex].value;
  let departement = select_departement.options[select_departement.selectedIndex].text;

  // Clear the map
  map.eachLayer(function (layer) {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });

  // Fetch installations based on selected year and department
  let response = await fetch(
    `../api/solar_manager/installations?annee=${annee}&id-departement=${departement}`
  );
  if (!response.ok) {
    console.error(error_message + response.statusText);
    return;
  }
  
  let installations = await response.json();
  
  // Add markers for each installation
  installations.forEach(installation => {
    var marker = L.marker([installation["Lat"], installation["Lon"]]).addTo(map);
    marker.bindPopup(
      `<div class='text-center'><b>${installation["Nom_standard"]}</b><br><a href='../details.php?id=${installation["ID"]}'>Voir plus de détails</a></div>`
    );
  });

}


document.getElementById("annee-installation-select").addEventListener("change", updateCarte);
document
  .getElementById("departements-select")
  .addEventListener("change", updateCarte);