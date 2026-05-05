titre = document.getElementById("titre");
epoque = document.getElementById("epoque");
description = document.getElementById("description");
prix = document.getElementById("prix");
image = document.getElementById("image");
budget = document.getElementById("argent");
encherisseur = document.getElementById("ench");
chrono = document.getElementById("temps");

encherirbutton = document.getElementById("submitenchere");
encherirbutton.addEventListener("click", encherir);
let argent = 0;
let prixactuel = 0;

function majobj() {
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

setInterval(function() {
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

function majbud() {
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

// Détecte le démarrage de la vente et active les mises à jour d'objet et de budget
setInterval(() => {
    fetch('api/verifstart.php')
        .then(r => r.json())
        .then(data => {
            if (data.start === 1) {
                if (!majObjInterval) majObjInterval = setInterval(majobj, 1000);
                if (!majBudInterval) majBudInterval = setInterval(majbud, 1000);
            }
        })
        .catch(error => console.error("Erreur AJAX :", error));
}, 1000);

// Détecte la fin de la vente et affiche les résultats — même logique que l'admin
setInterval(() => {
    fetch('api/results.php')
        .then(r => r.json())
        .then(data => {
            if (data.ended) {
                if (!resultsDismissed) showResults(data.results);
                if (majObjInterval) { clearInterval(majObjInterval); majObjInterval = null; }
                if (majBudInterval) { clearInterval(majBudInterval); majBudInterval = null; }
            } else {
                resultsOverlay.hidden = true;
                resultsDismissed = false;
            }
        });
}, 1000);

const toast = document.getElementById("toast");
let toastTimer = null;

function showToast(message) {
    toast.textContent = message;
    toast.hidden = false;
    toast.style.animation = "none";
    toast.offsetHeight; // reflow to restart animation
    toast.style.animation = "";
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.hidden = true; }, 3000);
}

function encherir() {
    Promise.all([majbud(), majobj()])
        .then(() => {
            if (argent < prixactuel + 500) {
                showToast("Budget insuffisant pour enchérir.");
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
