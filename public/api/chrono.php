<?php
try {
    require __DIR__ . '/../../includes/db.php';

    $stmt = $pdo->query("SELECT chrono FROM start WHERE id = 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    $chronoupdated = $data['chrono'] - 1;

    if ($chronoupdated <= 0) {
        $stmt = $pdo->query("SELECT obj FROM start WHERE id = 1"); // on récupère l'id de l'objet en vente
        $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->query("SELECT ench, prix FROM objects WHERE id = {$data2['obj']}"); // on récupère le nom de l'enchérisseur et le prix de l'objet
        $data3 = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($data3['ench'])) { // si il y a eu une enchère
            $stmt = $pdo->query("SELECT argent FROM users WHERE username = '{$data3['ench']}'"); // on récupère le budget de l'enchérisseur
            $data4 = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->query("UPDATE `users` SET `argent` = {$data4['argent']} - {$data3['prix']} WHERE `users`.`username` = '{$data3['ench']}';"); // on retire le prix de l'objet au budget de l'enchérisseur
        }
        
        $stmt = $pdo->query("UPDATE `start` SET `chrono` = 30 WHERE `start`.`id` = 1;"); // on remet le chrono a 30 secondes pour la prochaine vente
        $stmt = $pdo->query("UPDATE `start` SET `obj` = {$data2['obj']}+1 WHERE `start`.`id` = 1;"); // on passe à l'objet suivant
    }
    else {
        $stmt = $pdo->query("UPDATE `start` SET `chrono` = $chronoupdated WHERE `start`.`id` = 1;"); // on met a jour le chrono dans la bd en lui retirant une seconde
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>