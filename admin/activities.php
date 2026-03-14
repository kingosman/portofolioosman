<?php
session_start();
require_once 'includes/header.php';

$success = '';
$error = '';
$uploadDir = '../assets/images/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Fetch image path to delete file
    $stmt = $conn->prepare("SELECT image_path FROM activities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    if($res && file_exists('../' . $res['image_path']) && !empty($res['image_path'])) {
        unlink('../' . $res['image_path']);
    }

    $stmt = $conn->prepare("DELETE FROM activities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? 'photo';
    $order_num = (int)$_POST['order_num'] ?? 0;
    
    $imagePath = $_POST['existing_image'] ?? '';

    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $fileName = time() . '_' . $type . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            if (move_uploaded_file($tmpName, $targetFile)) {
                if ($imagePath && file_exists('../' . $imagePath)) {
                    unlink('../' . $imagePath);
                }
                $imagePath = 'assets/images/' . $fileName;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type.";
        }
    }

    if (!$error) {
        if (empty($imagePath)) {
            $error = "Image is required.";
        } else {
            if (isset($_POST['id']) && $_POST['id'] !== '') {
                $id = (int)$_POST['id'];
                $stmt = $conn->prepare("UPDATE activities SET title=?, type=?, image_path=?, order_num=? WHERE id=?");
                $stmt->bind_param("sssii", $title, $type, $imagePath, $order_num, $id);
                $stmt->execute();
                $success = "Updated successfully.";
            } else {
                $stmt = $conn->prepare("INSERT INTO activities (title, type, image_path, order_num) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $title, $type, $imagePath, $order_num);
                $stmt->execute();
                $success = "Added successfully.";
            }
        }
    }
}

$activities = $conn->query("SELECT * FROM activities ORDER BY type ASC, order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Photos & Logos</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>
<?php if ($error) echo "<div class='error'>$error</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Item</h3>
    <form method="POST" action="activities.php" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_data['image_path'] ?? '') ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Type</label>
            <select name="type">
                <option value="photo" <?= ($edit_data['type'] ?? '') === 'photo' ? 'selected' : '' ?>>Activity Photo/Slider</option>
                <option value="logo" <?= ($edit_data['type'] ?? '') === 'logo' ? 'selected' : '' ?>>Client/Institution Logo</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Title (Optional)</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Image Upload</label>
            <?php if ($edit_data && !empty($edit_data['image_path'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="../<?= htmlspecialchars($edit_data['image_path']) ?>" width="100" style="border-radius:4px; border:1px solid #ccc;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" <?= $edit_data ? '' : 'required' ?>>
        </div>
        
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required class="form-group" style="padding:10px; width:100px;">
        </div>
        
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="activities.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Image</th>
            <th>Type</th>
            <th>Title</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $activities->fetch_assoc()): ?>
        <tr>
            <td><img src="../<?= htmlspecialchars($row['image_path']) ?>" width="60" style="border-radius:4px;"></td>
            <td><span style="background:#eee;padding:3px 8px;border-radius:3px;font-size:0.8em;text-transform:uppercase;"><?= htmlspecialchars($row['type']) ?></span></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="activities.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="activities.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
