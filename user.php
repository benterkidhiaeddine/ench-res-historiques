<?php
    $objet = 1;
    $titre = "Sceptre";
    $epoque = "Epoque bien - XIV eme siecle";
    $description = "ceci est une decription pas trop longue du sceptre, il est en or et en diamants, il a appartenu a un roi de france";
    $prix = "1000 euros";
    $image = "img/obj" . $objet . ".jpg";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Pannel</title>
    <link rel="stylesheet" href="styleuser.css">
    <script src="scriptuser.js" defer></script>
</head>
<body>
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