<?php
$role = 1;
session_start();
require __DIR__ . '/../includes/db.php';

if (!empty($_POST['username2']) && !empty($_POST['password2']) && !empty($_POST['confirmPassword2'])) {
    $username = $_POST['username2'];
    $password = $_POST['password2'];
    $confirmPassword = $_POST['confirmPassword2'];

    if ($password === $confirmPassword) {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $check->bindParam(':username', $username);
        $check->execute();
        if ($check->fetch()) {
            header('Location: index.php?error=user_exists');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            header('Location: index.php?success=register');
        } else {
            header('Location: index.php?error=register');
        }
    } else {
        header('Location: index.php?error=password_mismatch');
    }
} else {
    header('Location: index.php?error=empty_fields');
}
exit;