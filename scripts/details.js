import { getLocalisation, getInstallateur, getPanneau, getOnduleur, displayErrorMessage } from "./utils.js";

//Create the icon for the solar panels
var solarIcon = L.icon({
  iconUrl: "../images/panneau-solaire-icone.png",
  iconSize: [38, 38], 
  iconAnchor: [19, 38], 
  popupAnchor: [0, -38], 
});

async function fillDetails(){
    let id = document.getElementById("installation-id").value;
    let installation_info = document.getElementById("installation-info");
    let location_info = document.getElementById("location-info");
    let panneau_infos = document.getElementById("panneau-info");
    let onduleur_infos = document.getElementById("onduleur-info");


    let response = await fetch(`../api/solar_manager/installations/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations de l'installation n°" + id);
        return;
    }

    let installation = await response.json();
    let location = await getLocalisation(installation.id_localisation);
    let panneau = await getPanneau(installation.id_panneau);
    let onduleur = await getOnduleur(installation.id_onduleur);
    let installateur = await getInstallateur(installation.id_installateur);

    //Take the ['0'] element of each
    location = location[0];
    panneau = panneau[0];
    onduleur = onduleur[0];
    installateur = installateur[0];



    //Clear existing content
    installation_info.innerHTML = "";
    panneau_infos.innerHTML = "";
    onduleur_infos.innerHTML = "";
    location_info.innerHTML = "";

    //Vérify if pente_optimum and orientation_optimum are not null, if set them to "Non renseigné"
    if (installation.Pente_optimum === null) {
        installation.Pente_optimum = "Non renseigné";
    }
    if (installation.Orientation_opti === null) {
        installation.Orientation_opti = "Non renseigné";
    }

    //Fill the HTML elements with the installation information
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
        <li><b>Commune</b> : ${installation.Nom_standard}</li>
        <li><b>Code Postal</b> : ${installation.Code_postal}</li>
        <li><b>Département</b> : ${installation.Dep_nom}</li>
        <li><b>Région</b> : ${installation.Reg_nom}</li>
        <li><b>Pays</b> : France</li>

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
    let response = await fetch(`../api/solar_manager/installations/?id=${id}`);
    if (!response.ok) {
        displayErrorMessage("Erreur lors de la récupération des informations de l'installation n°" + id);
        return;
    }

    let installation = await response.json();
    console.log(installation);

    var map = L.map("map").setView([installation.Lat, installation.Lon], 13);

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    var marker = L.marker([installation.Lat, installation.Lon], {
      icon: solarIcon,
    }).addTo(map);
    marker.bindPopup("<b>Installation n°" + id + "</b>").openPopup();
    console.log(marker);
}

document.addEventListener("DOMContentLoaded", () => {
    fillDetails();
    createMap();
});

