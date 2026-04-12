<?php
    $role = 1;
    session_start();
     $bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');
    if (!empty($_POST['username2']) && !empty($_POST['password2']) && !empty($_POST['confirmPassword2'])) {
        $username = $_POST['username2'];
        $password = $_POST['password2'];
        $confirmPassword = $_POST['confirmPassword2'];
        if ($password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $bd->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':role', $role);
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