titre = document.getElementById("titre");
epoque = document.getElementById("epoque");
description = document.getElementById("description");
prix = document.getElementById("prix");
image = document.getElementById("image");
budget = document.getElementById("argent");
encherisseur = document.getElementById("ench");
chrono = document.getElementById("temps");

encherirbutton = document.getElementById("submitenchere");
encherirbutton.addEventListener("click", encherir); // lance la fonction encherir() quand bouton clické
let argent = 0;
let prixactuel = 0;

function majobj() { // met a jour les infos des objets en vente dans le dom, a partir de la bd
    return fetch('api/majobj.php')
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
            encherisseur.textContent = "Meilleur encherisseur : " + data.ench;
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

majchrono = setInterval(function() { // met a jour le chrono de la vente dans le dom, a partir de la bd
    fetch('api/chronouser.php')
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            chrono.textContent = data.chrono + " sec";
        })
        .catch(error => console.error("Erreur AJAX :", error));
}, 1000);

function majbud() { // met a jour le budget de l'utilisateur dans le dom, a partir de la bd
    return fetch('api/majbud.php')
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Erreur budget :", data.error);
                return;
            }
            argent = data.argent;
            budget.textContent = data.argent + " €";
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

let resultsDismissed = false;
let resultsPollingInterval = null;
let majObjInterval = null;
let majBudInterval = null;

const resultsOverlay = document.getElementById("resultsOverlay");
const resultsList = document.getElementById("resultsList");
const resultsClose = document.getElementById("resultsClose");
const currentUser = document.body.getAttribute("data-user");

resultsClose.addEventListener("click", () => {
    resultsOverlay.hidden = true;
    resultsDismissed = true;
});

function verifstart() { // fonction qui verifie si la vente a commencé, et affiche un message d'attente si ce n'est pas le cas, sinon affiche les objets en vente et le budget de l'utilisateur
    fetch('api/verifstart.php')
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            if (data.start === 1) {
                if (startinterval) {
                    clearInterval(startinterval);
                    startinterval = null;
                }
                if (!majObjInterval) majObjInterval = setInterval(majobj, 1000); // met a jour les objets en vente toutes les secondes
                if (!majBudInterval) majBudInterval = setInterval(majbud, 1000); // met a jour le budget de l'utilisateur toutes les secondes
                
                // Si on recommence une session, on reset le flag de l'overlay
                resultsDismissed = false;
                resultsOverlay.hidden = true;
            } else {
                // Si start === 0, on regarde si c'est fini ou pas encore commencé
                pollResults();
            }
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

function pollResults() {
    fetch('api/results.php')
        .then(r => r.json())
        .then(data => {
            if (data.ended) {
                if (!resultsDismissed) {
                    showResults(data.results);
                }
                // Si c'est fini, on arrête de poller les détails de l'objet en cours
                if (majObjInterval) { clearInterval(majObjInterval); majObjInterval = null; }
                if (majBudInterval) { clearInterval(majBudInterval); majBudInterval = null; }
            } else {
                // Pas commencé ou reset
                resultsOverlay.hidden = true;
                resultsDismissed = false;
                if (!startinterval) startinterval = setInterval(verifstart, 1000);
            }
        });
}

function showResults(results) {
    resultsList.innerHTML = "";
    results.forEach(res => {
        const li = document.createElement("li");
        const isWinner = res.ench === currentUser;
        li.className = isWinner ? "winner-self" : "";
        li.innerHTML = `
            <strong>${res.titre}</strong> : 
            ${res.ench === 'Aucun encherisseur' ? 'Non vendu' : `Gagné par <b>${res.ench}</b> pour ${res.prix}€`}
        `;
        resultsList.appendChild(li);
    });
    resultsOverlay.hidden = false;
}


function encherir() { // fonction encherir qui verifie si l'utilisateur a assez d'argent pour encherir, et envoie une requette pour encherrir, met a jour la bd et appelle les fonctions majbud et majobj
    Promise.all([majbud(), majobj()])
        .then(() => {
            if (argent < prixactuel + 500) { // verifie si l'utilisateur a assez d'argent pour encherir
                alert("Vous n'avez pas assez d'argent pour encherir.");
                return;
            }

            return fetch('api/encherir.php')
                .then(response => {
                    if (!response.ok) throw new Error("Erreur réseau");
                    return response.json();
                })
                .then(data => {
                    if (data && data.error) {
                        console.error("Erreur enchere :", data.error);
                    }
                });
        })
        .catch(error => console.error("Erreur AJAX :", error));
}

majbud();
startinterval = setInterval(verifstart,1000);