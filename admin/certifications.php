<?php
session_start();
require_once 'includes/header.php';

$success = '';
$error = '';
$uploadDir = '../assets/images/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Fetch image path to delete file
    $stmt = $conn->prepare("SELECT image_path FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    if($res && file_exists('../' . $res['image_path']) && !empty($res['image_path'])) {
        unlink('../' . $res['image_path']);
    }

    $stmt = $conn->prepare("DELETE FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $order_num = (int)$_POST['order_num'] ?? 0;
    
    $imagePath = $_POST['existing_image'] ?? '';

    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($tmpName, $targetFile)) {
                // remove old file if exists
                if ($imagePath && file_exists('../' . $imagePath)) {
                    unlink('../' . $imagePath);
                }
                $imagePath = 'assets/images/' . $fileName;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
        }
    }

    if (!$error) {
        if (isset($_POST['id']) && $_POST['id'] !== '') {
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("UPDATE certifications SET title=?, description=?, image_path=?, order_num=? WHERE id=?");
            $stmt->bind_param("sssii", $title, $description, $imagePath, $order_num, $id);
            $stmt->execute();
            $success = "Updated successfully.";
        } else {
            if(!$imagePath) {
                $error = "Image is required for new certification.";
            } else {
                $stmt = $conn->prepare("INSERT INTO certifications (title, description, image_path, order_num) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $title, $description, $imagePath, $order_num);
                $stmt->execute();
                $success = "Added successfully.";
            }
        }
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

<h1>Manage Certifications</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>
<?php if ($error) echo "<div class='error'>$error</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Certification</h3>
    <form method="POST" action="certifications.php" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_data['image_path']) ?>">
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
            <label>Image</label>
            <?php if ($edit_data && $edit_data['image_path']): ?>
                <div style="margin-bottom: 10px;">
                    <img src="../<?= htmlspecialchars($edit_data['image_path']) ?>" width="150" style="border-radius:4px; border:1px solid #ccc;">
                </div>
                <p style="font-size:0.9em;color:#666;">Leave empty to keep the current image.</p>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" <?= !$edit_data ? 'required' : '' ?>>
        </div>
        
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required class="form-group" style="padding:10px; width:100px;">
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
            <th>Image</th>
            <th>Title</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $certs->fetch_assoc()): ?>
        <tr>
            <td><img src="../<?= htmlspecialchars($row['image_path']) ?>" width="80" height="auto" style="border-radius:4px;"></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
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
