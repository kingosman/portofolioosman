<?php
require_once dirname(__DIR__) . '/../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Osman Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f7f6; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; min-height: 100vh; color: #fff; padding: 20px 0; }
        .sidebar h2 { text-align: center; margin-top: 0; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li a { display: block; padding: 15px 20px; color: #ecf0f1; text-decoration: none; border-bottom: 1px solid #34495e; }
        .sidebar ul li a:hover, .sidebar ul li a.active { background: #34495e; }
        .content { flex: 1; padding: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;}
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group input[type="password"], .form-group input[type="file"], .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-sm { padding: 5px 10px; font-size: 0.9em; }
        .success { color: green; font-weight: bold; margin-bottom: 15px; }
        .error { color: red; font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CMS Admin</h2>
    <ul>
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <li><a href="index.php?page=dashboard" class="<?= $current_page === 'index.php' && ($_GET['page'] ?? '') !== 'settings' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="index.php?page=settings" class="<?= $current_page === 'index.php' && ($_GET['page'] ?? '') === 'settings' ? 'active' : '' ?>">Global Settings</a></li>
        <li><a href="organizations.php" class="<?= $current_page === 'organizations.php' ? 'active' : '' ?>">Organizations</a></li>
        <li><a href="experiences.php" class="<?= $current_page === 'experiences.php' ? 'active' : '' ?>">Experiences</a></li>
        <li><a href="skills.php" class="<?= $current_page === 'skills.php' ? 'active' : '' ?>">Skills</a></li>
        <li><a href="certifications.php" class="<?= $current_page === 'certifications.php' ? 'active' : '' ?>">Certifications</a></li>
        <li><a href="profile.php" class="<?= $current_page === 'profile.php' ? 'active' : '' ?>">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
