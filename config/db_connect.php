<?php
$db_host = 'localhost';
$db_port = 8889; // Default MAMP MySQL port
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
<!-- 
<?php
function connection() {
    $host = "localhost";
    $username = "root";
    $password = "Rak077871078"; // Leave empty if no password
    $database = "task_management";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?> -->