<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="en">
    <link href="/assests//css//bootstrap.min.css" rel="stylesheet">
    <title>Task Management Dashboard</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    header {
        background-color: #007bff;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        font-size: 24px;
    }

    header nav a {
        color: #fff;
        text-decoration: none;
        margin-left: 20px;
        font-size: 16px;
    }

    header nav a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <header>
        <h1>Task Management</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
</body>