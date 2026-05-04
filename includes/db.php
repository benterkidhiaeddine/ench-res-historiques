<?php

$db_host     = getenv('DB_HOST') ?: 'db';
$db_port     = getenv('DB_PORT') ?: '3306';
$db_name     = getenv('DB_NAME') ?: 'ench_hist';
$db_user     = getenv('DB_USER') ?: 'ench_user';
$db_password = getenv('DB_PASSWORD') ?: 'ench_password';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
