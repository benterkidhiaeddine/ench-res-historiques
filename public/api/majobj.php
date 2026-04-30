<?php

try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT titre, epoque, description, prix, image, ench FROM objects WHERE id = 1");
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
