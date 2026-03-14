<?php
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$page = $_GET['page'] ?? 'dashboard';

// Handle setting updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'settings') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE key_name = ?");
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
    }
    $success = "Settings updated successfully!";
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
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .success { color: green; font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CMS Admin</h2>
    <ul>
        <li><a href="?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="?page=settings" class="<?= $page === 'settings' ? 'active' : '' ?>">Global Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <?php if ($page === 'dashboard'): ?>
        <h1>Welcome to CMS Admin</h1>
        <p>Select a menu from the sidebar to start managing your portfolio.</p>
    <?php elseif ($page === 'settings'): ?>
        <h1>Global Settings</h1>
        <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
        <div class="card">
            <form method="POST">
                <div class="form-group">
                    <label>Slogan</label>
                    <input type="text" name="settings[slogan]" value="<?= htmlspecialchars(getSetting($conn, 'slogan')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Short Introduction</label>
                    <textarea name="settings[short_intro]" rows="4" required><?= htmlspecialchars(getSetting($conn, 'short_intro')) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Email Contact</label>
                    <input type="text" name="settings[email]" value="<?= htmlspecialchars(getSetting($conn, 'email')) ?>" required>
                </div>
                <div class="form-group">
                    <label>WhatsApp Number (Numeric only)</label>
                    <input type="text" name="settings[wa_number]" value="<?= htmlspecialchars(getSetting($conn, 'wa_number')) ?>" required>
                </div>
                <button type="submit" class="btn">Save Settings</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
