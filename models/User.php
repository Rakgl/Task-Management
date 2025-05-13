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

    public function create($email, $password, $role = 'user', $username, $phone, $position, $birthday, $profile) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, role, username, phone, position, birthday, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$email, $hashedPassword, $role, $username, $phone, $position, $birthday, $profile]);
    }

    public function updateProfilePicture($id, $filename) {
        $stmt = $this->pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
        return $stmt->execute([$filename, $id]);
    }

    // public function updateProfile($id, $data) {
    //     try {
    //         // Validate required fields
    //         if (empty($data['username'])) {
         
    //             throw new Exception('Username is required.');
    //         }
    //         if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
             
    //             throw new Exception('Invalid or missing email.');
    //         }
    //         if (empty($data['role']) || !in_array($data['role'], ['admin', 'user'])) {
              
    //             throw new Exception('Invalid role.');
    //         }

    //         // Check for duplicate email
    //         $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    //         $stmt->execute([$data['email'], $id]);
    //         if ($stmt->fetch()) {
    //             throw new Exception('Email is already in use.');
    //         }

    //         // Prepare data
    //         $profileData = [
    //             'username' => $data['username'],
    //             'email' => $data['email'],
    //             'phone' => $data['phone'] ?? null,
    //             'birthday' => $data['birthday'] ?? null,
    //             'profile' => $data['profile'] ?? null,
    //             'position' => $data['position'] ?? null,
    //             'role' => $data['role']
    //         ];

    //         // Update query
    //         $stmt = $this->pdo->prepare('UPDATE users SET username = ?, email = ?, phone = ?, position = ?, birthday = ?, role = ?, profile = ? WHERE id = ?');
    //         $result = $stmt->execute([
    //             $profileData['username'],
    //             $profileData['email'],
    //             $profileData['phone'],
    //             $profileData['birthday'],
    //             $profileData['profile'],
    //             $profileData['position'],
    //             $profileData['role'],
    //             $id
    //         ]);

    //         if ($result) {
    //             error_log("Profile updated for user ID $id: " . json_encode($profileData));
    //             // Verify update
    //             $stmt = $this->pdo->prepare('SELECT username, email, role, created_at FROM users WHERE id = ?');
    //             $stmt->execute([$id]);
    //             $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);
    //             error_log("Verified updated user ID $id: " . print_r($updatedUser, true));
    //         } else {
    //             error_log("Profile update failed for user ID $id");
    //         }
    //         return $result;
    //     } catch (Exception $e) {
    //         error_log("Profile update error for user ID $id: " . $e->getMessage());
    //         return false;
    //     }
    // }
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


    // public function update($email, $role = null, $id) {
    //     try {
    //         $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    //         $stmt->execute([$email, $id]);
    //         if ($stmt->fetch()) {
    //             error_log('Update failed: Email is already in use.');
    //             return false;
    //         }

    //         $setClause = "email = ?";
    //         $params = [$email];
    //         if ($role !== null) {
    //             $setClause .= ", role = ?";
    //             $params[] = $role;
    //         }
    //         $params[] = $id;
    //         $stmt = $this->pdo->prepare("UPDATE users SET $setClause WHERE id = ?");
    //         return $stmt->execute($params);
    //     } catch (Exception $e) {
    //         error_log('Update failed: ' . $e->getMessage());
    //         return false;
    //     }
        
    // }

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