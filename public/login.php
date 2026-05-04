<?php
    session_start();
    require __DIR__ . '/../includes/db.php';
    if (!empty($_POST['username1']) && !empty($_POST['password1'])) {
        $username = $_POST['username1'];
        $password = $_POST['password1'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: user.php"); // Redirige vers les enchères
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

?>
