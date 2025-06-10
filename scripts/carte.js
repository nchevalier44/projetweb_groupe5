import { displayErrorMessage, fillSelect } from "./utils.js";

//Content loaded when the DOM is ready
// This function fills the select element with options from the API and adds randoms pin to the map
document.addEventListener("DOMContentLoaded", () => {
  fillSelect("annee-installation-select");
  fillSelect("departements-select");
  getRandomInstallationsAndAddPin();
});


//Set the icon for the solar panels
var solarIcon = L.icon({
  iconUrl: "../images/panneau-solaire-icone.png",
  iconSize: [38, 38], 
  iconAnchor: [19, 38],
  popupAnchor: [0, -38], 
});

// Initialize the map
var map = L.map("map").setView([47.811399, 1.950145], 6);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);


//Add random pin when no date or departement is selected
async function getRandomInstallationsAndAddPin() {
  //Loading item spinner
  addSpinner();

  // Remove existing info if any
  removeInfo();

  // Fetch installations from the API
  let response = await fetch("../api/solar_manager/installations/");
  if (!response.ok) {
    displayErrorMessage("Erreur lors de la récupération des installations");
    return;
  }
  let installations = await response.json();
  //generate 50 random installations
  for (let i = 0; i < 50; i++) {
    let randomId = Math.floor(Math.random() * installations.length);
    let installation = installations[randomId];
    console.log(installation);
    var marker = L.marker([installation["Lat"], installation["Lon"]], {
      icon: solarIcon,
    }).addTo(map);
    marker.bindPopup(
      `<div class='text-center'><b>${installation["Nom_standard"]}</b><br><a href='details.php?id=${installation["id"]}'>Voir plus de détails</a></div>`
    );
  }
  // Remove the loading spinner
  removeSpinner();
}


//Update the map whenever the user selects a year or a department
async function updateCarte() {
  //add loading spinner
  addSpinner();
  // Remove existing info if any
  removeInfo();

  let select_annee = document.getElementById("annee-installation-select");
  let select_departement = document.getElementById("departements-select");
  let annee = select_annee.options[select_annee.selectedIndex].value;
  let departement = select_departement.options[select_departement.selectedIndex].value;
  let departementText = select_departement.options[select_departement.selectedIndex].text;
  let nbInstal = 0;

  if (annee === "" && departement === "") {
    // If no year and no department are selected, show random installations
    getRandomInstallationsAndAddPin();
    return;
  }

  // Clear the map
  map.eachLayer(function (layer) {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });

  // Fetch installations based on selected year and department
  console.log('departement:', departement);
  let response = await fetch(`../api/solar_manager/installations/?annee=${annee}&id-departement=${departement}`);
  if (!response.ok) {
    displayErrorMessage("Erreur lors de la récupération des installations");
    return;
  }

  let installations = await response.json();

  // Add markers for each installation
  installations.forEach((installation) => {
    var marker = L.marker([installation["Lat"], installation["Lon"]], {
      icon: solarIcon,
    }).addTo(map);
    marker.bindPopup(
      `<div class='text-center'><b>${installation["Nom_standard"]}</b><br><a href='details.php?id=${installation["id"]}'>Voir plus de détails</a></div>`
    );
    nbInstal++;
  });


  //Add an info message with the number of installations according to the selected year and department
  let innerText = "";
  if (annee === "") {
    innerText =
      nbInstal +
      " installations ont été réalisées dans le departement " +
      departementText;
  } else if (departement === "") {
    innerText = nbInstal + "  installations ont été réalisées en " + annee;
  } else {
    innerText =
      nbInstal +
      " installations ont été réalisées en " +
      annee +
      " dans le département " +
      departementText;
  }
  // Remove the loading spinner
  removeSpinner();
  // Add info message
  addInfo(innerText);
}

//Event listeners for the year and department select elements
document.getElementById("annee-installation-select").addEventListener("change", updateCarte);
document.getElementById("departements-select").addEventListener("change", updateCarte);


function addSpinner() {
  //add loading spinner
  let spinner = document.createElement("div");
  spinner.classList.add("spinner-border");
  spinner.setAttribute("role", "status");
  document.getElementsByClassName("map-container")[0].prepend(spinner);
  let spinnerWrapper = document.createElement("div");
  spinnerWrapper.classList.add("d-flex", "justify-content-center", "mt-3");
  spinnerWrapper.id = "spinner-wrapper";
  spinnerWrapper.appendChild(spinner);

  document.getElementsByClassName("map-container")[0].prepend(spinnerWrapper);
}

function removeSpinner() {
  if (document.getElementById("spinner-wrapper")) {
    document.getElementById("spinner-wrapper").remove();
  }
}

function addInfo(message) {
  let info = document.createElement("div");
  info.id = "info";
  info.classList.add(
    "alert",
    "alert-info",
    "d-flex",
    "align-items-center",
    "justify-content-center",
    "mt-3",
    "w-auto"
  );
  info.innerHTML = `
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
      <use xlink:href="#info-fill"/>
    </svg>
    <div class="mb-0">
      ${message}
    </div>
  `;
  // Remove existing info if any
  removeInfo();
  // Add the info before the map
  document.getElementsByClassName("map-container")[0].prepend(info);
}

function removeInfo() {
  let existingInfo = document.getElementById("info");
  if (existingInfo) {
    existingInfo.remove();
  }
}
