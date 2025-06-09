import { displayErrorMessage } from "./utils.js";

// This script is used to add buttons for modifying and deleting an installation
document.addEventListener("DOMContentLoaded", function () {
  const addButtonDiv = document.getElementById("modifbutton");
  if (addButtonDiv) {
    const btn = document.createElement("button");
    btn.className = "btn btn-primary mb-4";
    btn.textContent = "Modifier une installation";
    btn.onclick = () => {
      window.location.href = "../back/modif.php?id=" + document.getElementById("installation-id").value;
    };
    addButtonDiv.appendChild(btn);
  }

  const deleteButtonDiv = document.getElementById("deletebutton");
  if (deleteButtonDiv) {
    const btn = document.createElement("button");
    btn.className = "btn btn-danger mb-4";
    btn.textContent = "Supprimer une installation";
    btn.onclick = async () => {
      
      //Delete an installation
      // Check if the user really wants to delete the installation
      if (confirm("Êtes-vous sûr de vouloir supprimer cette installation ?")) {
        let installationId = document.getElementById("installation-id").value;

        //Get localisation id
        let response_loc = await fetch(`../api/solar_manager/installations/?id=${installationId}`);
        if (!response_loc.ok) {
          displayErrorMessage("Erreur lors de la récupération de la localisation. Veuillez réessayer.");
          return;
        }
        let json = await response_loc.json();
        let localisationsId = json.id_localisation;

        //Delete the isntallation
        let response = await fetch(`../api/solar_manager/installations/?id=${installationId}`, {
          method: "DELETE",
        });
        if(!response.ok){
          displayErrorMessage("Erreur lors de la suppression de l'installation. Veuillez réessayer.");
          return;
        } else{
          //Delete the localisation
          let response = await fetch(`../api/solar_manager/localisations/?id=${localisationsId}`, {
            method: "DELETE",
          });
          //window.location.href = "../back/index.php";
        }
      }
    };
    deleteButtonDiv.appendChild(btn);
  }
});