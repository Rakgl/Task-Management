<?php
$db_host = '127.0.0.1';
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

<?php
$db_host = 'norak-mysql-lzsxy5';
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