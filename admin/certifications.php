<?php
session_start();
require_once 'includes/header.php';

$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link_path = $_POST['link_path'] ?? ''; // Now using as link (Drive etc)
    $order_num = (int)$_POST['order_num'] ?? 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE certifications SET title=?, description=?, image_path=?, order_num=? WHERE id=?");
        $stmt->bind_param("sssii", $title, $description, $link_path, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO certifications (title, description, image_path, order_num) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $link_path, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$certs = $conn->query("SELECT * FROM certifications ORDER BY order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Certifications & Achievements</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Certification</h3>
    <form method="POST" action="certifications.php">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($edit_data['description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Certificate Link (e.g., Google Drive, LinkedIn Link)</label>
            <input type="text" name="link_path" value="<?= htmlspecialchars($edit_data['image_path'] ?? '') ?>" placeholder="https://drive.google.com/..." required>
        </div>
        
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required style="padding:10px; width:100px;">
        </div>
        
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="certifications.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Link</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $certs->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><a href="<?= htmlspecialchars($row['image_path']) ?>" target="_blank" style="color:blue; text-decoration:underline;">View Link</a></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="certifications.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="certifications.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
