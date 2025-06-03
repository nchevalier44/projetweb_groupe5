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
