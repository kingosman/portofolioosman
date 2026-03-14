<?php
session_start();
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed, $_SESSION['admin_id']);
            $stmt->execute();
            $success = "Password updated successfully.";
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>

<h1>Change Password</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>
<?php if ($error) echo "<div class='error'>$error</div>"; ?>

<div class="card">
    <form method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
