titre = document.getElementById("titre");
epoque = document.getElementById("epoque");
description = document.getElementById("description");
prix = document.getElementById("prix");
image = document.getElementById("image");

encherir = document.getElementById("submitenchere");
encherir.addEventListener("click", encherir);

function maj() {
    fetch('maj.php') 
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            prix.textContent = data.prix + " €";
            titre.textContent = data.titre;
            epoque.textContent = data.epoque;
            description.textContent = data.description;
            image.src = data.image;
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

function encherir() {
    // Logique pour soumettre une enchère
}

maj();