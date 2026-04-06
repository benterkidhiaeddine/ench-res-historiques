connexionButton = document.getElementById("connexionButton");
inscriptionButton = document.getElementById("inscriptionButton");
pageConnexion = document.getElementById("pageConnexion");
pageInscription = document.getElementById("pageInscription");

connexionButton.addEventListener("click", showConnexionPage);
inscriptionButton.addEventListener("click", showInscriptionPage);

function showConnexionPage() {
    pageInscription.style.display = "none";
    pageConnexion.style.display = "flex";
}

function showInscriptionPage() {
    pageConnexion.style.display = "none";
    pageInscription.style.display = "flex";
}