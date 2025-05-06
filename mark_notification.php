<?php
session_start();
require_once 'config/database.php';
require_once 'models/User.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$userModel = new User($pdo);
$notificationId = isset($_POST['id']) ? (int)$_POST['id'] : null;

if ($notificationId && $userModel->markNotificationAsRead($notificationId)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark as read']);
}
exit();