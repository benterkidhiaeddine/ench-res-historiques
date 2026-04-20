<?php
$bd = new PDO('mysql:host=127.0.0.1;port=3307;dbname=ench_hist', 'root', 'rootpassword');

$stmt = $bd->prepare("UPDATE start SET start = 1 WHERE id = 1");
$stmt->execute();