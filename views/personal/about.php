<?php
require_once './models/User.php';

$userModel = new User($pdo);
$user = $userModel->findById($_SESSION['user_id'] ?? null);
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">About</h2>
        </div>
        <div class="card-body">
            <h3 class="text-center text-primary mb-4">Project Overview</h3>
            <p class="lead text-center">
                The Task Management System is a web-based application designed to help users organize and track tasks
                efficiently. Hosted on a platform like Vercel, it features a modular structure with a dashboard, user
                profiles, and task management functionalities. The system supports multiple user roles, including admins
                and regular users, with role-based access control. Admins can manage tasks, create new ones, and oversee
                user profiles, including viewing, editing, and deleting user details. Regular users can view and edit
                their own profiles, which include fields like username, email, phone, position, birthday, and a personal
                profile description, along with the ability to upload profile pictures. The interface uses a card-based
                layout with responsive tables and stylized buttons, enhanced by Bootstrap for a modern look. The backend
                leverages PHP for routing and database interactions, with a User model handling CRUD operations, while
                JavaScript manages dynamic elements like collapsible sidebars. The project’s folder structure is
                organized with directories for public assets, includes, views, and models, ensuring scalability and
                maintainability.
            </p>
            <div class="text-center mt-4">
                <a href="index.php?page=dashboard" class="btn btn-secondary btn-lg">Back to Dashboard <i
                        class="fas fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    border-radius: 10px 10px 0 0;
}

.lead {
    font-size: 1.25rem;
    color: #333;
    line-height: 1.6;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.card {
    border-radius: 10px;
}

.card-body {
    padding: 20px;
}
</style>