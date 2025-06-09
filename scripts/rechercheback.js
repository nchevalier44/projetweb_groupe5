
//Adds a button to the page that redirects to the installation addition page
document.addEventListener("DOMContentLoaded", function () {
  const addButtonDiv = document.getElementById("addbutton");
  if (addButtonDiv) {
    const btn = document.createElement("button");
    btn.className = "btn btn-primary mb-4";
    btn.textContent = "Ajouter une installation";
    btn.onclick = () => {
      window.location.href = "../back/ajout.php";
    };
    addButtonDiv.appendChild(btn);
  }
});
