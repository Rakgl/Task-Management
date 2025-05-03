<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

$task_id = $_GET['id'] ?? null;
if (!$task_id || !is_numeric($task_id)) {
    header('Location: index.php?page=tasks');
    exit();
}

$deleted = $taskModel->delete($task_id, $_SESSION['user_id']);

if ($deleted) {
    header('Location: index.php?page=tasks');
    exit();
} else {
    header('Location: index.php?page=tasks');
    exit();
}
?>