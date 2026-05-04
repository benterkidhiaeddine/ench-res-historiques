<?php
header('Content-Type: application/json');
require __DIR__ . '/../../includes/db.php';
try {
    $stmt = $pdo->query("SELECT ended FROM start WHERE id = 1");
    $s = $stmt->fetch();
    $stmt = $pdo->query("SELECT id, titre, prix, ench, image FROM objects ORDER BY id ASC");
    echo json_encode([
        "ended"   => (bool) $s['ended'],
        "results" => $stmt->fetchAll(),
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
