<?php
session_start();
require_once 'includes/header.php';

$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM experiences WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'work';
    $date_range = $_POST['date_range'] ?? '';
    $link = $_POST['link'] ?? '';
    $order_num = (int)$_POST['order_num'] ?? 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE experiences SET title=?, description=?, category=?, date_range=?, link=?, order_num=? WHERE id=?");
        $stmt->bind_param("sssssii", $title, $description, $category, $date_range, $link, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO experiences (title, description, category, date_range, link, order_num) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $description, $category, $date_range, $link, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$experiences = $conn->query("SELECT * FROM experiences ORDER BY category, order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM experiences WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Experiences</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Experience</h3>
    <form method="POST" action="experiences.php">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Description (accepts basic text, multiple lines)</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($edit_data['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="work" <?= ($edit_data['category'] ?? '') === 'work' ? 'selected' : '' ?>>Work Experience</option>
                <option value="speaking" <?= ($edit_data['category'] ?? '') === 'speaking' ? 'selected' : '' ?>>Speaking / Seminars</option>
                <option value="writing" <?= ($edit_data['category'] ?? '') === 'writing' ? 'selected' : '' ?>>Written Works</option>
            </select>
        </div>
        <div class="form-group">
            <label>Date Range (e.g., 'Jan 2020 - Present')</label>
            <input type="text" name="date_range" value="<?= htmlspecialchars($edit_data['date_range'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Link (Highly recommended for Written Works)</label>
            <input type="text" name="link" value="<?= htmlspecialchars($edit_data['link'] ?? '') ?>" placeholder="https://...">
        </div>
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required style="padding:10px; width:100px;">
        </div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="experiences.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Date</th>
            <th>Link</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $experiences->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['date_range']) ?></td>
            <td><?= $row['link'] ? '<a href="'.htmlspecialchars($row['link']).'" target="_blank">View Link</a>' : '-' ?></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="experiences.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="experiences.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
