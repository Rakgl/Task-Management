<?php
require_once 'models/User.php';

$userModel = new User($pdo);
$user = $userModel->findById($_SESSION['user_id']);
$username = $user['username'] ?? 'Unknown';
$email = $user['email'] ?? 'Unknown';
$role = $user['role'] ?? 'Unknown';
?>

<style>
ul {
    list-style: none;
}

.sidebar {
    width: 200px;
    min-height: 89vh;
    max-height: auto;
    background-color: whitesmoke;
    border-bottom: none;
    border-top: none;
}

.list-group-item {
    height: 40px;
    cursor: pointer;
}

.list-group-item:hover {
    background-color: blue;
    color: white;
}

.list-group-item-action:active {
    background-color: blue;
}

.sub-task {
    display: none;
}

.sub-task.show {
    display: block;
}

.profile-header {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #d2d2d2;
    background-color: #f8f9fa;
}

.profile-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3498db;
    margin-bottom: 10px;
}

.profile-img-placeholder {
    width: 60px;
    height: 60px;
    background-color: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #666;
    border: 2px solid #3498db;
    margin: 0 auto 10px;
}

.profile-header h5 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.profile-header p {
    margin: 2px 0 0;
    font-size: 12px;
    color: #666;
}

.profile-section .list-group-item {
    background-color: #ecf0f1;
}

.profile-section .list-group-item:hover {
    background-color: #2ecc71;
    color: white;
}
</style>

<div class="sidebar col-md-3">
    <div class="profile-header">
        <?php if ($user['profile_picture']): ?>
        <img src="/uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
            class="profile-img">
        <?php else: ?>
        <div class="profile-img-placeholder">No Image</div>
        <?php endif; ?>
        <h5><?php echo htmlspecialchars($username); ?></h5>
        <p><?php echo htmlspecialchars($email); ?></p>
        <p>Role: <?php echo htmlspecialchars(ucfirst($role)); ?></p>
    </div>
    <div class="list-group">
        <a href="index.php?page=dashboard" class="list-group-item list-group-item-action">
            <i class="fas fa-chalkboard-teacher me-2 font-size-10"></i>Dashboard
        </a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-20"
            id="tasksToggle">
            <span><i class="fas fa-tasks me-2"></i>Tasks</span>
            <i id="arrow-down" class="fas fa-arrow-down"></i>
            <i id="arrow-up" class="fas fa-arrow-up" style="display: none;"></i>
        </div>
        <div class="collapse list-group submenu" id="tasksSubmenu">
            <a href="index.php?page=tasks" class="list-group-item list-group-item-action">
                <span><i class="fas fa-street-view me-4 font-size-10"></i>View Task</span>
            </a>
            <a href="index.php?page=task-create" class="list-group-item list-group-item-action">
                <span><i class="fas fa-plus me-4 font-size-10"></i>Create Task</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="promptEditTaskId()">
                <i class="fas fa-edit me-3 font-size-10"></i> Edit Task
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="promptDeleteTaskId()">
                <i class="fas fa-trash-alt font-size-10" style="margin-right: 20px;"></i> Delete Task
            </a>
        </div>
        <?php endif; ?>

        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-20 profile-section"
            id="profileToggle">
            <span><i class="fas fa-user me-2"></i>Profile</span>
            <i id="profileArrowDown" class="fas fa-arrow-down"></i>
            <i id="profileArrowUp" class="fas fa-arrow-up" style="display: none;"></i>
        </div>
        <div class="collapse list-group submenu" id="profileSubmenu">
            <?php if ($_SESSION['role'] !== 'admin'): ?>
            <a href="index.php?page=profile" class="list-group-item list-group-item-action">
                <span><i class="fas fa-eye me-4 font-size-10"></i>View Profile</span>
            </a>
            <a href="index.php?page=edit-profile-admin" class="list-group-item list-group-item-action">
                <span><i class="fas fa-edit me-4 font-size-10"></i>Edit Profile</span>
            </a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="index.php?page=profile" class="list-group-item list-group-item-action">
                <span><i class="fas fa-eye me-3 font-size-10"></i>View Profile</span>
            </a>

            <a href="index.php?page=list-profile" class="list-group-item list-group-item-action">
                <span><i class="fas fa-users me-3 font-size-10"></i>List Profiles</span>
            </a>
            <a href="index.php?page=create-profile" class="list-group-item list-group-item-action">
                <span><i class="fas fa-plus font-size-10" style="margin-right: 20px;"></i>Create Profile</span>
            </a>
            <a href="index.php?page=edit-profile-admin" class="list-group-item list-group-item-action">
                <i class="fas fa-edit me-3 font-size-10" style="margin-right: 20px;"></i>Edit Profile
            </a>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tasksToggle = document.getElementById('tasksToggle');
    const subTaskList = document.getElementById('tasksSubmenu');
    const arrowDown = document.getElementById('arrow-down');
    const arrowUp = document.getElementById('arrow-up');

    if (tasksToggle && subTaskList) {
        const bsCollapse = new bootstrap.Collapse(subTaskList, {
            toggle: false
        });

        subTaskList.addEventListener('show.bs.collapse', function() {
            arrowDown.style.display = 'none';
            arrowUp.style.display = 'inline';
        });

        subTaskList.addEventListener('hide.bs.collapse', function() {
            arrowDown.style.display = 'inline';
            arrowUp.style.display = 'none';
        });

        tasksToggle.addEventListener('click', function(e) {
            bsCollapse.toggle();
        });
    }

    const profileToggle = document.getElementById('profileToggle');
    const profileSubmenu = document.getElementById('profileSubmenu');
    const profileArrowDown = document.getElementById('profileArrowDown');
    const profileArrowUp = document.getElementById('profileArrowUp');

    const profileBsCollapse = new bootstrap.Collapse(profileSubmenu, {
        toggle: false
    });

    profileSubmenu.addEventListener('show.bs.collapse', function() {
        profileArrowDown.style.display = 'none';
        profileArrowUp.style.display = 'inline';
    });

    profileSubmenu.addEventListener('hide.bs.collapse', function() {
        profileArrowDown.style.display = 'inline';
        profileArrowUp.style.display = 'none';
    });

    profileToggle.addEventListener('click', function(e) {
        profileBsCollapse.toggle();
    });
});

function promptEditTaskId() {
    const taskId = prompt("Please enter the Task ID to edit:");
    if (taskId !== null && taskId.trim() !== "" && !isNaN(taskId)) {
        window.location.href = "index.php?page=task-edit&id=" + encodeURIComponent(taskId.trim());
    } else if (taskId !== null) {
        alert("Please enter a valid numeric Task ID.");
    }
}

function promptDeleteTaskId() {
    const taskId = prompt("Please enter the Task ID to Delete:");
    if (taskId !== null && taskId.trim() !== "" && !isNaN(taskId)) {
        window.location.href = "index.php?page=task-delete&id=" + encodeURIComponent(taskId.trim());
    } else if (taskId !== null) {
        alert("Please enter a valid numeric Task ID.");
    }
}
</script>