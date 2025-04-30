<?php
// views/auth/register.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once './config/db_connect.php';

$error   = '';
$success = '';
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email            = trim($_POST['email']           ?? '');
    $password         =        $_POST['password']      ?? '';
    $confirm_password =        $_POST['confirm_password'] ?? '';

    if ($email === '' || $password === '' || $confirm_password === '') {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check existing email
        $stmt = $conn->prepare(
            'SELECT user_id FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'This email is already registered.';
        } else {
            $stmt->close();
            // Insert new user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                'INSERT INTO users (email, password) VALUES (?, ?)'
            );
            $stmt->bind_param('ss', $email, $hash);

            if ($stmt->execute()) {
                $success = 'Registration successful! You may now <a href="index.php?page=login">login</a>.';
                $email   = '';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assests//css//bootstrap.min.css">
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Register</h3>

                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php elseif ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form action="index.php?page=register" method="post" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" id="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="index.php?page=login">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="/assests//js//bootstrap.min.js"></script>