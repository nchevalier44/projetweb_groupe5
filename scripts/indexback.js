//Add an info message to the page when the user is logged in as an admin
document.addEventListener("DOMContentLoaded", function () {
  let info = document.createElement("div");
  info.id = "info";
  info.classList.add(
    "alert",
    "alert-info",
    "d-flex",
    "align-items-center",
    "justify-content-center",
    "mt-3",
    "w-auto",
    "container"
  );
  info.innerHTML = `
  
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
      <use xlink:href="#info-fill"/>
    </svg>
    <div class="mb-0">
      Vous êtes connecté en tant qu'administrateur.
    </div>
  `;

  // Add the info
  document.getElementsByTagName("header")[0].insertAdjacentElement("afterend", info);
  //Timeout to remove the info after 5 seconds
  setTimeout(() => {document.getElementById("info").remove();}, 5000);
});


