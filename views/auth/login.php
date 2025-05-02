<?php
// views/auth/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login.php');
    exit();
}

require_once './config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Please fill in both username and password.';
    } else {
        try {
            // Check if the username exists
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assests//css//bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    .input-group-append .input-group-text {
        background-color: transparent;
        border-left: none;
        padding: 0.375rem 0.75rem;
    }

    .input-group .form-control {
        border-right: 0;
    }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Login</h3>

                    <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>

                    <form action="index.php?page=login" method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control" type="text" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <div class="input-group-append">
                                    <!-- Show/Hide password button -->
                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="index.php?page=register">Don't have an account? Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="/assests//js//bootstrap.min.js"></script>

<script>
document.getElementById('username').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        document.getElementById('password').focus();
    }
});

const togglePassword = document.getElementById('togglePassword');
const passwordField = document.getElementById('password');

togglePassword.addEventListener('click', function() {
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;

    const icon = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    togglePassword.innerHTML = `<i class="${icon}"></i>`;
});
</script>