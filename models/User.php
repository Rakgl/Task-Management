<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch user by ID
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch user by email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user (e.g., for registration)
    public function create($email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$email, $hashedPassword, $role]);
    }
    // public function create($email, $password) {
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    //     $stmt = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    //     return $stmt->execute([$email, $hashedPassword]);
    // }

    // Update user details (e.g., for profile updates)
    public function update($id, $email, $role = null) {
        $setClause = "email = ?";
        $params = [$email, $id];
        if ($role !== null) {
            $setClause .= ", role = ?";
            $params[] = $role;
        }
        $stmt = $this->pdo->prepare("UPDATE users SET $setClause WHERE id = ?");
        return $stmt->execute($params);
    }

    // role user 
    public function getRole($id) {
        $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['role'] ?? 'user';
    }
}
?>