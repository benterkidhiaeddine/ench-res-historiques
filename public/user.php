<?php
    $objet = 0;
    $titre = "La vente va bientot commencer";
    $epoque = " ";
    $description = " ";
    $prix = "0€";
    $image = "assets/img/0debut.png";
    session_start();

// Si la variable "username" n'existe pas, c'est que l'user n'est pas logué
    if (!isset($_SESSION['username'])) {
        header("Location: index.html"); // On le renvoie à la connexion
        exit(); // On arrête l'exécution du script
    }

    $username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Pannel</title>
    <link rel="stylesheet" href="assets/css/styleuser.css">
    <script src="assets/js/scriptuser.js" defer></script>
</head>
<body>
    welcome <?php echo $username; ?> //temporaire pour afficher le nom de l'utilisateur connecté
    <div id="divobjet">
        <img id="image" src="<?php echo $image; ?>">
        <h2 id="epoque"><?php echo $epoque; ?></h2>
        <h1 id="titre"><?php echo $titre; ?></h1>
        <p id="description"><?php echo $description; ?></p>
    </div>
    <div id="divbp">
        <div id="divbudget">
            <h1>Budget</h1>
            <h2 id="argent">180000€</h2>
        </div>
        <div id="divprix">
            <h2>prix actuel</h2>
            <h1 id="prix"><?php echo $prix; ?></h1>
            <button id="submitenchere" type="submit">Encherir +500€</button>
        </div>
    </div>
</body>
</html>