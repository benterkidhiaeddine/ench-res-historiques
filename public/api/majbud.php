<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Non connecté"]);
    exit;
}

$username = $_SESSION['username'];

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->prepare("SELECT argent FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    if (!$data) {
        echo json_encode(["error" => "Utilisateur introuvable"]);
        exit;
    }

    echo json_encode([
        "argent" => $data['argent']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>