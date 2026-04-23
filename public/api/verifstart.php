<?php

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT start FROM start WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode([
        "start" => $data['start']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
