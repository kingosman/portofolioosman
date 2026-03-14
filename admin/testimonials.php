<?php
session_start();
require_once 'includes/header.php';

$success = '';
$error = '';
$uploadDir = '../assets/images/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT image_path FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && !empty($res['image_path']) && file_exists('../' . $res['image_path'])) unlink('../' . $res['image_path']);

    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $content = $_POST['content'] ?? '';
    $order_num = (int)$_POST['order_num'] ?? 0;
    $image_path = $_POST['existing_image'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $fileName = 'testi_' . time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $targetFile)) {
            if ($image_path && file_exists('../' . $image_path)) unlink('../' . $image_path);
            $image_path = 'assets/images/' . $fileName;
        }
    }

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE testimonials SET name=?, position=?, content=?, image_path=?, order_num=? WHERE id=?");
        $stmt->bind_param("ssssii", $name, $position, $content, $image_path, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO testimonials (name, position, content, image_path, order_num) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $position, $content, $image_path, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$items = $conn->query("SELECT * FROM testimonials ORDER BY order_num ASC");
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Testimonials</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Testimonial</h3>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_data): ?><input type="hidden" name="id" value="<?= $edit_data['id'] ?>"><input type="hidden" name="existing_image" value="<?= $edit_data['image_path'] ?>"><?php endif; ?>
        <div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required></div>
        <div class="form-group"><label>Position/Company</label><input type="text" name="position" value="<?= htmlspecialchars($edit_data['position'] ?? '') ?>"></div>
        <div class="form-group"><label>Testimonial Content</label><textarea name="content" rows="4" required><?= htmlspecialchars($edit_data['content'] ?? '') ?></textarea></div>
        <div class="form-group">
            <label>Person Image</label>
            <?php if ($edit_data && $edit_data['image_path']): ?><img src="../<?= $edit_data['image_path'] ?>" width="80" style="border-radius:50%;"><br><?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group"><label>Order</label><input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>"></div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
    </form>
</div>

<div class="card">
    <table>
        <tr><th>Image</th><th>Name</th><th>Content</th><th>Actions</th></tr>
        <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
            <td><img src="../<?= $row['image_path'] ?>" width="40" style="border-radius:50%;"></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= substr(htmlspecialchars($row['content']), 0, 50) ?>...</td>
            <td>
                <a href="testimonials.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="testimonials.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
