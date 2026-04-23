<?php

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT argent FROM users WHERE username = 'amine'");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode([
        "argent" => $data['argent']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
