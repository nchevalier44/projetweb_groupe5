import { fillSelect } from "./utils.js";

var map = L.map("map").setView([47.274972, -1.505077], 13);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

var marker = L.marker([47.274972, -1.505077]).addTo(map);

marker.bindPopup("<div class='text-center'><b>Carquefou - Puissance 5kW</b><br><a href='../details.php?id=1'>Voir plus de détails</a></div>").openPopup();

document.addEventListener("DOMContentLoaded", () => {
  fillSelect("annee-installation-select");
  fillSelect("departements-select");
});