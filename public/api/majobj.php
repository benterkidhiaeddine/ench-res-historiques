<?php

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT obj FROM start WHERE id = 1");
    $data2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT titre, epoque, description, prix, image, ench FROM objects WHERE id = {$data2['obj']}");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode([
        "titre" => $data['titre'],
        "epoque" => $data['epoque'],
        "description" => $data['description'],
        "prix" => $data['prix'],
        "image" => $data['image'],
        "ench"  => $data['ench']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
