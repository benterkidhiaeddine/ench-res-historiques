<?php
session_start();
header('Content-Type: application/json'); // On le met tout en haut

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "Non connecté"]);
    exit;
}


$user = $_SESSION['username'];

try {
    require __DIR__ . '/../../includes/db.php'; 

    // 1. Récupérer l'argent de l'utilisateur
    $stmt = $pdo->prepare("SELECT argent FROM users WHERE username = :user");
    $stmt->execute(['user' => $user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Récupérer l'ID de l'objet en cours
    $stmt = $pdo->query("SELECT obj FROM start WHERE id = 1");
    $startData = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentObjId = $startData['obj'];

    // 3. Récupérer le prix de l'objet
    $stmt = $pdo->prepare("SELECT prix FROM objects WHERE id = :id");
    $stmt->execute(['id' => $currentObjId]);
    $objData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$objData) {
        echo json_encode(["error" => "Aucun objet en cours de vente"]);
        exit;
    }

    // --- LOGIQUE DE L'ENCHÈRE ---
    if ($userData['argent'] >= ($objData['prix'] + 500)) {
        
        $new_prix = $objData['prix'] + 500;

        // On met à jour les deux tables
        $stmt = $pdo->prepare("UPDATE objects SET prix = :new_prix WHERE id = :obj_id");
        $stmt->execute(['new_prix' => $new_prix, 'obj_id' => $currentObjId]);
        $stmt = $pdo->prepare("UPDATE objects SET ench = :user WHERE id = :obj_id");
        $stmt->execute(['user' => $user, 'obj_id' => $currentObjId]);
        
        // On met à jour les variables pour le JSON final
        $objData['prix'] = $new_prix;
        $stmt = $pdo->query("UPDATE `start` SET `chrono` = 30 WHERE `start`.`id` = 1;");
        $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UN SEUL ECHO à la fin avec toutes les infos
    echo json_encode([
        "argent" => $userData['argent'],
        "prix" => $objData['prix'],
        "obj" => $currentObjId
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}