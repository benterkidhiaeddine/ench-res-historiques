<?php

try {
    $bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');
    
    $stmt = $bd->query("SELECT start FROM start WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode([
        "start" => $data['start']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}