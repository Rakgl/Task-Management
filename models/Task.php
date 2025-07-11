<?php
class  Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTasksByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskCountByUserId($user_id, $status = null) {
        try {
            $query = "SELECT COUNT(*) FROM tasks WHERE user_id = ?";
            $params = [$user_id];
            if ($status !== null) {
                $query .= " AND status = ?";
                $params[] = $status;
            }
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting tasks for user_id $user_id: " . $e->getMessage());
            return 0;
        }
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $title, $description, $status = 'pending') {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $description, $status]);
    }

    public function update($id, $title, $description, $status) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function createNotification($taskId, $userId, $taskTitle) {
        $message = "You have been assigned the task: $taskTitle.";
        $stmt = $this->pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        return $stmt->execute([$userId, $message]);
    }
}
?>