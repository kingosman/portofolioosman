<?php
session_start();
require_once 'includes/header.php';

$success = '';
$error = '';
$uploadDir = '../assets/images/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT thumbnail FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && !empty($res['thumbnail']) && file_exists('../' . $res['thumbnail'])) unlink('../' . $res['thumbnail']);

    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $link = $_POST['link'] ?? '';
    $category = $_POST['category'] ?? 'news';
    $order_num = (int)$_POST['order_num'] ?? 0;
    $thumbnail = $_POST['existing_thumbnail'] ?? '';

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['thumbnail']['tmp_name'];
        $fileName = 'news_' . time() . '_' . basename($_FILES['thumbnail']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $targetFile)) {
            if ($thumbnail && file_exists('../' . $thumbnail)) unlink('../' . $thumbnail);
            $thumbnail = 'assets/images/' . $fileName;
        }
    }

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE news SET title=?, link=?, category=?, thumbnail=?, order_num=? WHERE id=?");
        $stmt->bind_param("ssssii", $title, $link, $category, $thumbnail, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO news (title, link, category, thumbnail, order_num) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $link, $category, $thumbnail, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$items = $conn->query("SELECT * FROM news ORDER BY order_num ASC");
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage News & Videos</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Item</h3>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_data): ?><input type="hidden" name="id" value="<?= $edit_data['id'] ?>"><input type="hidden" name="existing_thumbnail" value="<?= $edit_data['thumbnail'] ?>"><?php endif; ?>
        <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Link</label><input type="text" name="link" value="<?= htmlspecialchars($edit_data['link'] ?? '') ?>" required></div>
        <div class="form-group"><label>Category</label>
            <select name="category">
                <option value="news" <?= ($edit_data['category'] ?? '') === 'news' ? 'selected' : '' ?>>News</option>
                <option value="video" <?= ($edit_data['category'] ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
            </select>
        </div>
        <div class="form-group">
            <label>Thumbnail</label>
            <?php if ($edit_data && $edit_data['thumbnail']): ?><img src="../<?= $edit_data['thumbnail'] ?>" width="100"><br><?php endif; ?>
            <input type="file" name="thumbnail" accept="image/*">
        </div>
        <div class="form-group"><label>Order</label><input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>"></div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
    </form>
</div>

<div class="card">
    <table>
        <tr><th>Thumbnail</th><th>Title</th><th>Category</th><th>Actions</th></tr>
        <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
            <td><img src="../<?= $row['thumbnail'] ?>" width="50"></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['category'] ?></td>
            <td>
                <a href="news.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="news.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
