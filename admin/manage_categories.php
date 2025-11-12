<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$edit_category = null;

// Handle Add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['category_name']);
    if (!empty($name)) {
        $sql = "INSERT INTO skill_categories (category_name) VALUES ('$name')";
        if ($conn->query($sql) === TRUE) {
            $message = "New category added successfully!";
        } else { $message = "Error: " . $conn->error; }
    } else {
        $message = "Error: Category name cannot be empty.";
    }
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['category_name']);
    if (!empty($name)) {
        $sql = "UPDATE skill_categories SET category_name = '$name' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $message = "Category updated successfully!";
        } else { $message = "Error: " . $conn->error; }
    } else {
        $message = "Error: Category name cannot be empty.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // 'ON DELETE CASCADE' in the database (from Step 1) automatically deletes skills in this category.
    $sql = "DELETE FROM skill_categories WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Category (and its skills) deleted successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM skill_categories WHERE id = $id");
    if ($result->num_rows == 1) {
        $edit_category = $result->fetch_assoc();
    }
}

// Fetch all
$categories_result = $conn->query("SELECT * FROM skill_categories ORDER BY category_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skill Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Skill Categories</h1>
            <div>
                <a href="../index.php" target="_blank" class="view-site-btn">View Site</a>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>
        <a href="index.php" class="back-link">&larr; Back to Dashboard</a>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2>Category List</h2>
        <table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories_result && $categories_result->num_rows > 0): ?>
                    <?php while($row = $categories_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td class="actions">
                                <a href="manage_categories.php?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="manage_categories.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('WARNING: Deleting a category will also delete all skills inside it. Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="manage_categories.php" method="POST" class="admin-form">
            <h2><?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?></h2>
            
            <?php if ($edit_category): ?>
                <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
            <?php endif; ?>

            <div>
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($edit_category['category_name'] ?? ''); ?>" required>
            </div>
            <div>
                <?php if ($edit_category): ?>
                    <button type="submit" name="update_category" class="update-btn">Update Category</button>
                    <a href="manage_categories.php" class="cancel-link">Cancel Edit</a>
                <?php else: ?>
                    <button type="submit" name="add_category">Add Category</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>