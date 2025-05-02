<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link rel="stylesheet" href="/assests//css//bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    .navbar {
        height: auto;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=dashboard">Task Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard" style="display: none;">Dashboard</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" id="toggleFullScreen">
                            <i class="fas fa-expand"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="display: none;" href="javascript:void(0);" id="toggleExitFullScreen">
                            <i class="fas fa-compress"></i>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout">Logout</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=register">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>

<script>
const toggleFullScreen = document.getElementById('toggleFullScreen');
const toggleExitFullScreen = document.getElementById('toggleExitFullScreen');

// Function to enter fullscreen
function enterFullScreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

// Function to exit fullscreen
function exitFullScreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
}

// Add event listeners
toggleFullScreen.addEventListener('click', () => {
    enterFullScreen();
    toggleFullScreen.style.display = 'none';
    toggleExitFullScreen.style.display = 'block';
});

toggleExitFullScreen.addEventListener('click', () => {
    exitFullScreen();
    toggleFullScreen.style.display = 'block';
    toggleExitFullScreen.style.display = 'none';
});

document.querySelectorAll('a.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        // Prevent exiting fullscreen when any link is clicked
        // We do not call exitFullScreen here
    });
});
</script>