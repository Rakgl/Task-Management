<?php
class  Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all tasks for a user
    public function getTasksByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single task by ID
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new task
    public function create($user_id, $title, $description, $status = 'pending') {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $description, $status]);
    }

    // Update a task
    public function update($id, $title, $description, $status) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $status, $id]);
    }

    // Delete a task
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>