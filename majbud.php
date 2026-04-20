<?php

try {
    $bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');
    
    $stmt = $bd->query("SELECT argent FROM users WHERE username = 'amine'");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode([
        "argent" => $data['argent']
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}