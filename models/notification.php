<?php
class Notification {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId) {
        $stmt = $this->pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
        return $stmt->execute([$notificationId]);
    }
}