<?php
$message = '';
$messageType = 'error'; // pour styling CSS si besoin

if (!empty($_GET['error'])) {
    switch ($_GET['error']) {
        case 'login':
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
            break;
        case 'register':
            $message = "Erreur d'inscription : veuillez vérifier les informations saisies.";
            break;
        case 'empty_fields':
            $message = "Veuillez remplir tous les champs.";
            break;
        case 'password_mismatch':
            $message = "Les mots de passe ne correspondent pas.";
            break;
        default:
            $message = "Une erreur est survenue. Veuillez réessayer.";
            break;
    }
} elseif (!empty($_GET['success'])) {
    $messageType = 'success';
    switch ($_GET['success']) {
        case 'register':
            $message = "Inscription réussie ! Vous pouvez vous connecter.";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchères historiques</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <h1>Enchères Historiques</h1>
    <p id="info"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <div id="container">
        <button id="connexionButton">Connexion</button>
        <button id="inscriptionButton">S'inscrire</button>
        <div id="pageConnexion">
            <form action="login.php" method="POST">
                <label for="username1">Nom d'utilisateur:</label>
                <input type="text" id="username1" name="username1">
                <label for="password1">Mot de passe:</label>
                <input type="password" id="password1" name="password1">
                <button id="submitConnexion1" type="submit">Se connecter</button>
            </form>
        </div>
        <div id="pageInscription">
            <form action="register.php" method="POST">
                <label for="username2">Nom d'utilisateur:</label>
                <input type="text" id="username2" name="username2">
                <label for="password2">Mot de passe:</label>
                <input type="password" id="password2" name="password2">
                <label for="confirmPassword2">Confirmer le mot de passe:</label>
                <input type="password" id="confirmPassword2" name="confirmPassword2">
                <button id="submitInscription2" type="submit">S'inscrire</button>
            </form>
        </div>
    </div>
</body>
</html>