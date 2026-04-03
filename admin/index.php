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

    // Handle Hero Image Upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $tmpName = $_FILES['hero_image']['tmp_name'];
        $fileName = 'hero_' . time() . '_' . basename($_FILES['hero_image']['name']);
        $targetFile = $uploadDir . $fileName;
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['hero_image']['type'], $allowedTypes)) {
            if (move_uploaded_file($tmpName, $targetFile)) {
                $dbPath = 'assets/images/' . $fileName;
                $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE key_name = 'hero_image'");
                $stmt->bind_param("s", $dbPath);
                $stmt->execute();
            }
        }
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
        <form method="POST" enctype="multipart/form-data">
            <h3>General Settings</h3>
            <div class="form-group">
                <label>Slogan</label>
                <input type="text" name="settings[slogan]" value="<?= htmlspecialchars(getSetting($conn, 'slogan')) ?>" required>
            </div>
            <div class="form-group">
                <label>Hero Image (PNG without background recommended)</label>
                <?php $currentHero = getSetting($conn, 'hero_image'); ?>
                <?php if ($currentHero): ?>
                    <div style="margin-bottom:10px;">
                        <img src="../<?= htmlspecialchars($currentHero) ?>" height="100" style="background:#eee; border-radius:8px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="hero_image" accept="image/*">
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

            <hr style="margin:30px 0; border:1px solid #eee;">
            <h3>Downloads & Social Media</h3>
            <div class="form-group">
                <label>CV Download Link</label>
                <input type="text" name="settings[cv_link]" value="<?= htmlspecialchars(getSetting($conn, 'cv_link')) ?>" placeholder="URL to your CV">
            </div>
            <div class="form-group">
                <label>Portfolio Download Link</label>
                <input type="text" name="settings[portfolio_link]" value="<?= htmlspecialchars(getSetting($conn, 'portfolio_link')) ?>" placeholder="URL to your Portfolio">
            </div>
            <br>
            <div class="form-group">
                <label>Instagram URL</label>
                <input type="text" name="settings[social_instagram]" value="<?= htmlspecialchars(getSetting($conn, 'social_instagram')) ?>" placeholder="https://instagram.com/...">
            </div>
            <div class="form-group">
                <label>LinkedIn URL</label>
                <input type="text" name="settings[social_linkedin]" value="<?= htmlspecialchars(getSetting($conn, 'social_linkedin')) ?>" placeholder="https://linkedin.com/in/...">
            </div>
            <div class="form-group">
                <label>Facebook URL</label>
                <input type="text" name="settings[social_facebook]" value="<?= htmlspecialchars(getSetting($conn, 'social_facebook')) ?>" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label>TikTok URL</label>
                <input type="text" name="settings[social_tiktok]" value="<?= htmlspecialchars(getSetting($conn, 'social_tiktok')) ?>" placeholder="https://tiktok.com/@...">
            </div>
            <div class="form-group">
                <label>YouTube URL</label>
                <input type="text" name="settings[social_youtube]" value="<?= htmlspecialchars(getSetting($conn, 'social_youtube')) ?>" placeholder="https://youtube.com/...">
            </div>

            <hr style="margin:30px 0; border:1px solid #eee;">
            <h3>SEO & Social Media Meta</h3>
            <p style="font-size:0.85rem; color:#666; margin-bottom:15px;">Optimize how your website appears on Google and Social Media (WhatsApp, FB, etc).</p>
            <div class="form-group">
                <label>SEO Title (Browser Tab & Social Share Title)</label>
                <input type="text" name="settings[seo_title]" value="<?= htmlspecialchars(getSetting($conn, 'seo_title')) ?>" placeholder="Default: Osman Nur Chaidir | Mentor Bisnis & Mentor Pengusaha Muda Malang Terbaik">
            </div>
            <div class="form-group">
                <label>SEO Description (Meta Tag & Snippet Summary)</label>
                <textarea name="settings[seo_description]" rows="3" placeholder="Default: Cari Mentor Bisnis Malang? Osman Nur Chaidir adalah rekomendasi mentor pengusaha muda Malang..."><?= htmlspecialchars(getSetting($conn, 'seo_description')) ?></textarea>
            </div>
            <div class="form-group">
                <label>SEO Keywords (Comma separated)</label>
                <input type="text" name="settings[seo_keywords]" value="<?= htmlspecialchars(getSetting($conn, 'seo_keywords')) ?>" placeholder="Mentor Pengusaha, Mentor Pengusaha Muda Malang, Rekomendasi Mentor Bisnis Malang, Mentor Bisnis Malang, Pengusaha Muda Malang">
            </div>
            <div class="form-group">
                <label>Social Share Image / OG Image (External URL)</label>
                <input type="text" name="settings[og_image]" value="<?= htmlspecialchars(getSetting($conn, 'og_image')) ?>" placeholder="Leave empty to use Hero Image. Recommended size: 1200x630">
            </div>
    

            <button type="submit" class="btn">Save Settings</button>
        </form>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
