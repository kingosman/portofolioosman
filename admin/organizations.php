<?php
session_start();
require_once 'includes/header.php';

$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM organizations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $type = $_POST['type'] ?? 'business';
    $order_num = (int)$_POST['order_num'] ?? 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE organizations SET name=?, role=?, type=?, order_num=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $role, $type, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO organizations (name, role, type, order_num) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $role, $type, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$orgs = $conn->query("SELECT * FROM organizations ORDER BY order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM organizations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Organizations & Businesses</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Organization</h3>
    <form method="POST" action="organizations.php">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" name="role" value="<?= htmlspecialchars($edit_data['role'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="type">
                <option value="business" <?= ($edit_data['type'] ?? '') === 'business' ? 'selected' : '' ?>>Business</option>
                <option value="organization" <?= ($edit_data['type'] ?? '') === 'organization' ? 'selected' : '' ?>>Organization</option>
            </select>
        </div>
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required class="form-group" style="padding:10px; width:100px;">
        </div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="organizations.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Type</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $orgs->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="organizations.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="organizations.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
