<?php

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT chrono FROM start WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode([
        "chrono" => $data['chrono']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}