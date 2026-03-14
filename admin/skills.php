<?php
session_start();
require_once 'includes/header.php';

$success = '';
$error = '';
$uploadDir = '../assets/images/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $conn->prepare("SELECT thumbnail FROM skills WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && !empty($res['thumbnail']) && file_exists('../' . $res['thumbnail'])) unlink('../' . $res['thumbnail']);

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
    $thumbnail = $_POST['existing_thumbnail'] ?? '';

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['thumbnail']['tmp_name'];
        $fileName = 'skill_' . time() . '_' . basename($_FILES['thumbnail']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $targetFile)) {
            if ($thumbnail && file_exists('../' . $thumbnail)) unlink('../' . $thumbnail);
            $thumbnail = 'assets/images/' . $fileName;
        }
    }

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE skills SET name=?, category=?, portfolio_link=?, thumbnail=?, order_num=? WHERE id=?");
        $stmt->bind_param("ssssii", $name, $category, $portfolio_link, $thumbnail, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO skills (name, category, portfolio_link, thumbnail, order_num) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $category, $portfolio_link, $thumbnail, $order_num);
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
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <input type="hidden" name="existing_thumbnail" value="<?= $edit_data['thumbnail'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Skill Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="digital_marketing" <?= ($edit_data['category'] ?? '') === 'digital_marketing' ? 'selected' : '' ?>>Digital Marketing</option>
                <option value="business_mentor" <?= ($edit_data['category'] ?? '') === 'business_mentor' ? 'selected' : '' ?>>Business Mentor</option>
                <option value="website_development" <?= ($edit_data['category'] ?? '') === 'website_development' ? 'selected' : '' ?>>Website Development</option>
                <option value="sociology" <?= ($edit_data['category'] ?? '') === 'sociology' ? 'selected' : '' ?>>Sociology</option>
                <option value="others" <?= ($edit_data['category'] ?? '') === 'others' ? 'selected' : '' ?>>Others</option>
            </select>
        </div>
        <div class="form-group">
            <label>Thumbnail (Recommended for Web Dev)</label>
            <?php if ($edit_data && $edit_data['thumbnail']): ?>
                <img src="../<?= $edit_data['thumbnail'] ?>" width="60" style="margin-bottom:10px;"><br>
            <?php endif; ?>
            <input type="file" name="thumbnail" accept="image/*">
        </div>
        <div class="form-group">
            <label>Portfolio/Proof Link (Optional)</label>
            <input type="text" name="portfolio_link" value="<?= htmlspecialchars($edit_data['portfolio_link'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required>
        </div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
    </form>
</div>

<div class="card">
    <table>
        <tr><th>Thumbnail</th><th>Name</th><th>Category</th><th>Order</th><th>Actions</th></tr>
        <?php while ($row = $skills->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if($row['thumbnail']): ?>
                    <img src="../<?= $row['thumbnail'] ?>" width="40">
                <?php else: ?>
                    <span style="color:#ccc;">None</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= ucwords(str_replace('_', ' ', $row['category'])) ?></td>
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
