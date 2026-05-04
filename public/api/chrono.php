<?php
try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT chrono, obj FROM start WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    $chronoupdated = $data['chrono'] - 1;
    $currentObjId = $data['obj'];

    if ($chronoupdated <= 0) {
        $stmt = $pdo->prepare("SELECT ench, prix FROM objects WHERE id = :id");
        $stmt->execute(['id' => $currentObjId]);
        $data3 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data3 && $data3['ench'] !== 'Aucun encherisseur' && !empty($data3['ench'])) {
            $stmt = $pdo->prepare("UPDATE users SET argent = argent - :prix WHERE username = :user");
            $stmt->execute(['prix' => $data3['prix'], 'user' => $data3['ench']]);
        }

        $stmt = $pdo->prepare("SELECT id FROM objects WHERE id > :id ORDER BY id ASC LIMIT 1");
        $stmt->execute(['id' => $currentObjId]);
        $next = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($next) {
            $stmt = $pdo->prepare("UPDATE start SET chrono = 30, obj = :next WHERE id = 1");
            $stmt->execute(['next' => $next['id']]);
        } else {
            $stmt = $pdo->query("UPDATE start SET chrono = 0, start = 0, ended = 1 WHERE id = 1");
        }
    } else {
        $stmt = $pdo->prepare("UPDATE start SET chrono = :c WHERE id = 1");
        $stmt->execute(['c' => $chronoupdated]);
    }

    echo json_encode(["chrono" => max(0, $chronoupdated)]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
