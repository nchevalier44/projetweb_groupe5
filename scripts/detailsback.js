document.addEventListener("DOMContentLoaded", function () {
  const addButtonDiv = document.getElementById("modifbutton");
  if (addButtonDiv) {
    const btn = document.createElement("button");
    btn.className = "btn btn-primary mb-4";
    btn.textContent = "Modifier une installation";
    btn.onclick = () => {
      window.location.href = "../back/modif.php";
    };
    addButtonDiv.appendChild(btn);
  }
});
