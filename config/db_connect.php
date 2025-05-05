<?php
$db_host = 'norak-mysql-rty4hc';
$db_port = 3306; 
$db_user = 'root';
$db_password = 'root';
$db_db = 'norak';

try {
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_db", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>