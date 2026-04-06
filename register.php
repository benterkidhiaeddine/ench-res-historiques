<?php
    session_start();
    $bd = new PDO('mysql:host=localhost;dbname=users', 'root', ''); //remplacer host et dbname
    if (!empty($_POST['username2']) && !empty($_POST['password2']) && !empty($_POST['confirmPassword2'])) {
        $username = $_POST['username2'];
        $password = $_POST['password2'];
        $confirmPassword = $_POST['confirmPassword2'];
        if ($password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $bd->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            if ($stmt->execute()) {
                echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                echo "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        } else {
            echo "Les mots de passe ne correspondent pas.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
    ?>