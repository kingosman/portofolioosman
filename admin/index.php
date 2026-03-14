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
    <h1>Global Settings & Facts</h1>
    <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
    <div class="card">
        <form method="POST">
            <h3>General Settings</h3>
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

            <hr style="margin:30px 0; border:1px solid #eee;">
            <h3>Data & Facts (Leave empty to hide)</h3>
            <div class="form-group">
                <label>Pengalaman Berwirausaha</label>
                <input type="text" name="settings[fact_wirausaha]" value="<?= htmlspecialchars(getSetting($conn, 'fact_wirausaha')) ?>" placeholder="e.g., 5+ Tahun">
            </div>
            <div class="form-group">
                <label>Jumlah Bisnis Dimentori</label>
                <input type="text" name="settings[fact_bisnis_dimentori]" value="<?= htmlspecialchars(getSetting($conn, 'fact_bisnis_dimentori')) ?>" placeholder="e.g., 50+">
            </div>
            <div class="form-group">
                <label>Jumlah Anggota Organisasi Dipimpin</label>
                <input type="text" name="settings[fact_anggota_dipimpin]" value="<?= htmlspecialchars(getSetting($conn, 'fact_anggota_dipimpin')) ?>" placeholder="e.g., 1000+">
            </div>
            <div class="form-group">
                <label>Jumlah Audiens Sebagai Pembicara</label>
                <input type="text" name="settings[fact_audiens]" value="<?= htmlspecialchars(getSetting($conn, 'fact_audiens')) ?>" placeholder="e.g., 5000+">
            </div>
            <div class="form-group">
                <label>Jumlah Prestasi</label>
                <input type="text" name="settings[fact_prestasi]" value="<?= htmlspecialchars(getSetting($conn, 'fact_prestasi')) ?>" placeholder="e.g., 20+">
            </div>
            <div class="form-group">
                <label>Jumlah Pembicara Kegiatan</label>
                <input type="text" name="settings[fact_pembicara]" value="<?= htmlspecialchars(getSetting($conn, 'fact_pembicara')) ?>" placeholder="e.g., 100+">
            </div>

            <button type="submit" class="btn">Save Settings</button>
        </form>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
