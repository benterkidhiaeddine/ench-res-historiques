bouton = document.getElementById("start");
bouton.addEventListener("click", start);

function start() { // fonction qui lance la vente
    fetch('api/start.php')
    let chronointerval = setInterval(chrono, 1000); // met a jour le chrono toutes les secondes
    }
function chrono() { // fonction qui met a jour le chrono de la vente dans la bd
    fetch('api/chrono.php')}
