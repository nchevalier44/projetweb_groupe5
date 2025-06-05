import { getLocalisation, getInstallateur, getPanneau, getOnduleur } from "./utils.js";


async function fillDetails(){
    let id = document.getElementById("installation-id").value;
    let installation_info = document.getElementById("installation-info");
    let location_info = document.getElementById("location-info");
    let panneau_infos = document.getElementById("panneau-info");
    let onduleur_infos = document.getElementById("onduleur-info");


    /*let response = await fetch(`/api/solar_manager/installation/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations de l'installation : " + response.statusText);
        return;
    }

    let installation = await response.json();
    let location = await getLocalisation(installation.id_localisation);
    let panneau = await getPanneau(installation.id_panneau);
    let onduleur = await getOnduleur(installation.id_onduleur);
    let installateur = await getInstallateur(installation.id_installateur);*/
    let installation = {id: 1, Iddoc: "12345", Mois_installation: '05', An_installation: "2023", Nb_panneaux: 10, Nb_onduleurs: 2, Puissance_crete: 5000, Pente: 30, Pente_optimum: 35, Orientation: "Sud", Orientation_opti: "Sud-Est", Surface: 50, Production_pvgis: 6000, id_localisation: 1, id_panneau: 1, id_onduleur: 1, id_installateur: 1};
    let location = {Lat: 48.8566, Lon: 2.3522, Nom_standard: "Paris", Code_postal: "75000", Dep_nom: "Paris", Reg_nom: "Île-de-France", Country: "France"};
    let panneau = {Panneaux_marque: "Marque A", Panneaux_modele: "Modèle A"};
    let onduleur = {Onduleur_marque: "Marque B", Onduleur_modele: "Modèle B"};
    let installateur = {Installateur: "Installateur C"};

    //Clear existing content
    installation_info.innerHTML = "";
    panneau_infos.innerHTML = "";
    onduleur_infos.innerHTML = "";
    location_info.innerHTML = "";

    installation_info.innerHTML = `
        <li><b>Id Doc</b> : ${installation.Iddoc}</li>
        <li><b>Date d'installation</b> : ${installation.Mois_installation}/${installation.An_installation}</li>
        <li><b>Nombre de panneaux</b> : ${installation.Nb_panneaux}</li>
        <li><b>Nombre d'onduleurs</b> : ${installation.Nb_onduleurs}</li>
        <li><b>Puissance crête</b> : ${installation.Puissance_crete}</li>
        <li><b>Pente</b> : ${installation.Pente}</li>
        <li><b>Pente optimal</b> : ${installation.Pente_optimum}</li>
        <li><b>Orientation</b> : ${installation.Orientation}</li>
        <li><b>Orientation optimal</b> : ${installation.Orientation_opti}</li>
        <li><b>Surface</b> : ${installation.Surface}</li>
        <li><b>Production PVGIS</b> : ${installation.Production_pvgis}</li>
        <li><b>Installateur</b> : ${installateur.Installateur}</li>
    `;

    location_info.innerHTML = `
        <li><b>Latitude</b> : ${location.Lat}</li>
        <li><b>Longitude</b> : ${location.Lon}</li>
        <li><b>Commune</b> : ${location.Nom_standard}</li>
        <li><b>Code Postal</b> : ${location.Code_postal}</li>
        <li><b>Département</b> : ${location.Dep_nom}</li>
        <li><b>Région</b> : ${location.Reg_nom}</li>
        <li><b>Pays</b> : ${location.Country}</li>

    `;

    panneau_infos.innerHTML = `
        <li><b>Marque</b> : ${panneau.Panneaux_marque}</li>
        <li><b>Modèle</b> : ${panneau.Panneaux_modele}</li>
    `;

    onduleur_infos.innerHTML = `
        <li><b>Marque</b> : ${onduleur.Onduleur_marque}</li>
        <li><b>Modèle</b> : ${onduleur.Onduleur_modele}</li>
    `;
}

async function createMap() {
    let id = document.getElementById("installation-id").value;
    /*let response = await fetch(`/api/solar_manager/installation/${id}`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des informations de l'installation : " + response.statusText);
        return;
    }

    let installation = await response.json();*/
    let installation = {lat: 48.8566, lon: 2.3522}; // Example coordinates for Paris

    var map = L.map("map").setView([installation.lat, installation.lon], 13);

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    var marker = L.marker([installation.lat, installation.lon]).addTo(map);
    marker.bindPopup("<b>Installation n°" + id + "</b>").openPopup();
}

document.addEventListener("DOMContentLoaded", () => {
    fillDetails();
    createMap();
});

