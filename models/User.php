<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$email, $hashedPassword, $role]);
    }

    public function updateProfilePicture($id, $filename) {
        $stmt = $this->pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
        return $stmt->execute([$filename, $id]);
    }

    public function updateProfile($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE users SET username = ?, phone = ?, birthday = ?, profile = ?, position = ? WHERE id = ?');
        return $stmt->execute([
            $data['username'],
            $data['phone'],
            $data['birthday'],
            $data['profile'],
            $data['position'],
            $id
        ]);
    }

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

    public function getRole($id) {
        $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['role'] ?? 'user';
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'");
        $stmt->execute();
        $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['admin_count'];

        $user = $this->findById($id);
        if ($user && $user['role'] === 'admin' && $adminCount <= 1) {
            return false; 
        }

        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>