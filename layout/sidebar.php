<style>
ul {
    list-style: none;
}

.sidebar {
    width: 200px;
    height: 89vh;
    max-height: auto;
    background-color: whitesmoke;
    border: 1px solid #000;
    border-bottom: none;
    border-top: none;
}

.list-group-item {
    height: 40px;
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
</style>

<div class="sidebar col-md-3">
    <div class="list-group">
        <a href="index.php?page=dashboard" class="list-group-item list-group-item-action">
            <i class="fas fa-chalkboard-teacher me-2 font-size-10"></i>Dashboard
        </a>

        <?php if($_SESSION['user_id'] == 1):?>
        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-20"
            data-bs-toggle="collapse" data-bs-target="#tasksSubmenu" aria-expanded="false" aria-controls="tasksSubmenu">
            <span><i class="fas fa-tasks me-2"></i> Tasks</span>
            <i id="arrow-down" class="fas fa-arrow-down"></i>
            <i id="arrow-up" class="fas fa-arrow-up" style="display: none;"></i>
        </div>
        <div class="collapse list-group submenu" id="tasksSubmenu">
            <a href="index.php?page=tasks" class="list-group-item list-group-item-action">
                <span>
                    <i class="fas fa-street-view me-4 font-size-10"></i>View Task
                </span>
            </a>
            <a href="index.php?page=task-create" class="list-group-item list-group-item-action">
                <span>
                    <i class="fas fa-plus me-4 font-size-10"></i>Create Task
                </span>
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="promptEditTaskId()">
                <i class="fas fa-edit me-3 font-size-10"></i> Edit Task
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="promptDeleteTaskId()">
                <i class="fas fa-trash-alt font-size-10" style="margin-right: 20px;"></i> Delete Task
            </a>
        </div>
        <?php endif; ?>

        <a href="index.php?page=profile" class="list-group-item list-group-item-action">
            <i class="fas fa-user-circle me-2 font-size-10"></i>Profile
        </a>
    </div>
</div>

<script>
document.getElementById('tasksLink').addEventListener('click', function(e) {
    e.preventDefault();

    const subTaskList = document.getElementById('tasksSubmenu');
    const arrowDown = document.getElementById('arrow-down');
    const arrowUp = document.getElementById('arrow-up');

    subTaskList.classList.toggle('show');

    if (subTaskList.classList.contains('show')) {
        arrowDown.style.display = 'none';
        arrowUp.style.display = 'inline';
    } else {
        arrowDown.style.display = 'inline';
        arrowUp.style.display = 'none';
    }
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