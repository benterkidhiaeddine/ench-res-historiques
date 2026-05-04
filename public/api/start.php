<?php
require __DIR__ . '/../../includes/db.php';

$stmt = $pdo->prepare("UPDATE start SET start = 1, ended = 0 WHERE id = 1");
$stmt->execute();
