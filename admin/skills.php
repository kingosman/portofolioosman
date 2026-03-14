<?php
session_start();
require_once 'includes/header.php';

$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? 'digital_marketing';
    $portfolio_link = $_POST['portfolio_link'] ?? '';
    $order_num = (int)$_POST['order_num'] ?? 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE skills SET name=?, category=?, portfolio_link=?, order_num=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $category, $portfolio_link, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO skills (name, category, portfolio_link, order_num) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $category, $portfolio_link, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$skills = $conn->query("SELECT * FROM skills ORDER BY category, order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Skills</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Skill</h3>
    <form method="POST" action="skills.php">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Skill Name (e.g., SEO, HTML/CSS, Leadership)</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="digital_marketing" <?= ($edit_data['category'] ?? '') === 'digital_marketing' ? 'selected' : '' ?>>Digital Marketing</option>
                <option value="business_mentor" <?= ($edit_data['category'] ?? '') === 'business_mentor' ? 'selected' : '' ?>>Business Mentor</option>
                <option value="website_development" <?= ($edit_data['category'] ?? '') === 'website_development' ? 'selected' : '' ?>>Website Development</option>
            </select>
        </div>
        <div class="form-group">
            <label>Portfolio/Proof Link (Optional)</label>
            <input type="text" name="portfolio_link" value="<?= htmlspecialchars($edit_data['portfolio_link'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required class="form-group" style="padding:10px; width:100px;">
        </div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="skills.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Link</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $skills->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= str_replace('_', ' ', ucwords($row['category'], '_')) ?></td>
            <td><?= $row['portfolio_link'] ? '<a href="'.htmlspecialchars($row['portfolio_link']).'" target="_blank">Link</a>' : '-' ?></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="skills.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="skills.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
