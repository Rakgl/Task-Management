<style>
ul {
    list-style: none;
}

.sidebar {
    width: 200px;
    height: 100vh;
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
        <a href="index.php?page=dashboard" class="list-group-item list-group-item-action">Dashboard</a>
        <a href="index.php?page=tasks" class="list-group-item list-group-item-action" id="tasksLink">Tasks</a>
        <ul class="sub-task" id="subTaskList">
            <li><a href="index.php?page=task-create" class="list-group-item list-group-item-action">Create Task</a></li>
            <li><a href="index.php?page=task-edit" class="list-group-item list-group-item-action">Edit Task</a></li>
        </ul>
        <a href="index.php?page=profile" class="list-group-item list-group-item-action">Profile</a>
    </div>
</div>

<script>
document.getElementById('tasksLink').addEventListener('click', function(e) {
    e.preventDefault();
    const subTaskList = document.getElementById('subTaskList');
    subTaskList.classList.toggle('show');
});
</script>