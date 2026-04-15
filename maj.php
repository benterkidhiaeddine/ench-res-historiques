<?php

try {
    $bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');
    
    $stmt = $bd->query("SELECT titre, epoque, description, prix, image FROM objects WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode([
        "titre" => $data['titre'],
        "epoque" => $data['epoque'],
        "description" => $data['description'],
        "prix" => $data['prix'],
        "image" => $data['image']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}