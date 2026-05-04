const startBtn = document.getElementById("start");
const resetBtn = document.getElementById("reset");
const resultsOverlay = document.getElementById("resultsOverlay");
const resultsList = document.getElementById("resultsList");
const resultsClose = document.getElementById("resultsClose");

let resultsDismissed = false;
let chronoInterval = null;

startBtn.addEventListener("click", start);
resetBtn.addEventListener("click", reset);
resultsClose.addEventListener("click", () => {
    resultsOverlay.hidden = true;
    resultsDismissed = true;
});

function start() { // fonction qui lance la vente
    fetch('api/start.php');
    if (!chronoInterval) chronoInterval = setInterval(chrono, 1000); // met a jour le chrono toutes les secondes
}

function reset() {
    if (!confirm("Voulez-vous vraiment réinitialiser la session ?")) return;
    fetch('api/reset.php', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                resultsOverlay.hidden = true;
                resultsDismissed = false;
                if (chronoInterval) {
                    clearInterval(chronoInterval);
                    chronoInterval = null;
                }
            }
        });
}

function chrono() { // fonction qui met a jour le chrono de la vente dans la bd
    fetch('api/chrono.php');
}

// Polling permanent pour l'admin pour voir les résultats
setInterval(() => {
    fetch('api/results.php')
        .then(r => r.json())
        .then(data => {
            if (data.ended) {
                if (!resultsDismissed) {
                    showResults(data.results);
                }
                if (chronoInterval) {
                    clearInterval(chronoInterval);
                    chronoInterval = null;
                }
            } else {
                resultsOverlay.hidden = true;
                resultsDismissed = false;
            }
        });
}, 1000);

function showResults(results) {
    resultsList.innerHTML = "";
    results.forEach(res => {
        const li = document.createElement("li");
        li.innerHTML = `
            <strong>${res.titre}</strong> : 
            ${res.ench === 'Aucun encherisseur' ? 'Non vendu' : `Gagné par <b>${res.ench}</b> pour ${res.prix}€`}
        `;
        resultsList.appendChild(li);
    });
    resultsOverlay.hidden = false;
}
