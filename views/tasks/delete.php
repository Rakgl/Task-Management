<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

$task_id = $_GET['id'] ?? null;
if (!$task_id || !is_numeric($task_id)) {
    header('Location: index.php?page=tasks');
    exit();
}

if ($_SESSION['user_id'] != 1) {
    header('Location: index.php?page=dashboard');
    exit();
}

try {
    $deleted = $taskModel->delete($task_id, $_SESSION['user_id']);
    if ($deleted) {
        header('Location: index.php?page=tasks&success=delete_success');
    } else {
        header('Location: index.php?page=tasks&error=delete_failed');
    }
} catch (PDOException $e) {
    error_log("Delete error for task_id $task_id: " . $e->getMessage());
    header('Location: index.php?page=tasks&error=database_error');
} finally {
    exit();
}
?>