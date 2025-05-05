<?php
<<<<<<< HEAD
$db_host = 'norak-mysql-rty4hc';
=======
$db_host = '127.0.0.1';
>>>>>>> 083977bb5b0ea17771770ddac391cfdbf011cde4
$db_port = 3306; 
$db_user = 'root';
$db_password = 'Rak077871078';
$db_db = 'task_management';

try {
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_db", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>