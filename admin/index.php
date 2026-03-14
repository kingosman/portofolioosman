<?php
session_start();
require_once 'includes/header.php';

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

<?php require_once 'includes/footer.php'; ?>
