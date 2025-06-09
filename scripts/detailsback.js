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
    btn.onclick = () => {
      if (confirm("Êtes-vous sûr de vouloir supprimer cette installation ?")) {
        console.log("Suppression de l'installation avec l'ID : " + document.getElementById("installation-id").value);
        //TODO : suppress element in database
      }
    };
    deleteButtonDiv.appendChild(btn);
  }
});
