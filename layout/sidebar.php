<style>
.sidebar {
    width: 250px;
    background-color: #343a40;
    color: #fff;
    position: fixed;
    top: 70px;
    /* Adjust based on header height */
    left: 0;
    height: calc(100vh - 110px);
    /* Adjust based on header and footer height */
    padding: 20px;
}

.sidebar h2 {
    font-size: 20px;
    margin-bottom: 20px;
}

.sidebar a {
    color: #adb5bd;
    text-decoration: none;
    display: block;
    padding: 10px 0;
    font-size: 16px;
}

.sidebar a:hover {
    color: #fff;
    background-color: #495057;
    padding-left: 10px;
    transition: all 0.3s;
}
</style>
<div class="sidebar">
    <h2>Menu</h2>
    <a href="dashboard.php">Home</a>
    <a href="tasks.php">Manage Tasks</a>
    <a href="profile.php">Profile</a>
    <a href="settings.php">Settings</a>
</div>