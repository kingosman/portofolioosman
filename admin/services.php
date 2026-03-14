<?php
session_start();
require_once 'includes/header.php';

$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Deleted successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $terms = $_POST['terms'] ?? '';
    $order_num = (int)$_POST['order_num'] ?? 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE services SET name=?, price=?, description=?, terms=?, order_num=? WHERE id=?");
        $stmt->bind_param("ssssii", $name, $price, $description, $terms, $order_num, $id);
        $stmt->execute();
        $success = "Updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO services (name, price, description, terms, order_num) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $price, $description, $terms, $order_num);
        $stmt->execute();
        $success = "Added successfully.";
    }
}

$services = $conn->query("SELECT * FROM services ORDER BY order_num ASC");

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>

<h1>Manage Services & Rate Card</h1>
<?php if ($success) echo "<div class='success'>$success</div>"; ?>

<div class="card">
    <h3><?= $edit_data ? 'Edit' : 'Add New' ?> Service</h3>
    <form method="POST" action="services.php">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Service Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" value="<?= htmlspecialchars($edit_data['price'] ?? '') ?>" required placeholder="e.g. Rp 5.000.000 or 'Contact for Price'">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" required><?= htmlspecialchars($edit_data['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Terms & Conditions (Optional)</label>
            <textarea name="terms" rows="3"><?= htmlspecialchars($edit_data['terms'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Order Number</label>
            <input type="number" name="order_num" value="<?= $edit_data['order_num'] ?? 0 ?>" required class="form-group" style="padding:10px; width:100px;">
        </div>
        <button type="submit" class="btn"><?= $edit_data ? 'Update' : 'Add' ?></button>
        <?php if ($edit_data): ?>
            <a href="services.php" class="btn" style="background:#7f8c8d;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Data List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Order</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $services->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
            <td><?= $row['order_num'] ?></td>
            <td>
                <a href="services.php?edit=<?= $row['id'] ?>" class="btn btn-sm">Edit</a>
                <a href="services.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
