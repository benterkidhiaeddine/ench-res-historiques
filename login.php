<?php
    session_start();
    $bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');
    if (!empty($_POST['username1']) && !empty($_POST['password1'])) {
        $username = $_POST['username1'];
        $password = $_POST['password1'];
        $stmt = $bd->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: user.php"); // Redirige vers les enchères
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

?>