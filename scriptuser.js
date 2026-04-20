titre = document.getElementById("titre");
epoque = document.getElementById("epoque");
description = document.getElementById("description");
prix = document.getElementById("prix");
image = document.getElementById("image");

encherir = document.getElementById("submitenchere");
encherir.addEventListener("click", encherir); // lance la fonction encherir() quand bouton clické
let argent = 0;
let prixactuel = 0;

function majobj() { // met a jour les infos des objets en vente dans le dom, a partir de la bd
    fetch('majobj.php') 
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
            prixactuel = data.prix;
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

function majbud() { // met a jour le budget de l'utilisateur dans le dom, a partir de la bd
    fetch('majbud.php') 
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            argent = data.argent;
            argent.textContent = data.argent + " €";
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

function verifstart() { // fonction qui verifie si la vente a commencé, et affiche un message d'attente si ce n'est pas le cas, sinon affiche les objets en vente et le budget de l'utilisateur
    fetch('verifstart.php') 
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            if (data.start === 1) {
                clearInterval(startinterval);
                setInterval(majobj, 1000); // met a jour les objets en vente toutes les 5 secondes
                setInterval(majbud, 1000); // met a jour le budget de l'utilisateur toutes les 5 secondes
        }})
        .catch(error => console.error("Erreur AJAX :", error));
}


function encherir() { // fonction encherir qui verifie si l'utilisateur a assez d'argent pour encherir, et envoie une requette pour encherrir, met a jour la bd et appelle les fonctions majbud et majobj
    majbud();
    if (argent >= prixactuel + 500) {
        
    }
}

majbud();
startinterval = setInterval(verifstart,1000);