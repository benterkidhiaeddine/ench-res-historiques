<?php
require 'db.php';

// SELECT
$stmt = $pdo->prepare("SELECT * FROM users;");
$stmt->execute([]);
$user = $stmt->fetch();

var_dump($user);