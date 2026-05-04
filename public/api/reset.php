<?php
header('Content-Type: application/json');
require __DIR__ . '/../../includes/db.php';
try {
    $pdo->beginTransaction();
    $pdo->exec("UPDATE objects SET prix = prix_initial, ench = 'Aucun encherisseur'");
    $pdo->exec("UPDATE start SET start = 0, obj = 1, chrono = 30, ended = 0 WHERE id = 1");
    $pdo->exec("UPDATE users SET argent = 180000");
    $pdo->commit();
    echo json_encode(["ok" => true]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
