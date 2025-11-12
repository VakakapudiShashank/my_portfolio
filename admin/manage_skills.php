<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$edit_skill = null;

// Handle Add Skill
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_skill'])) {
    $category_id = intval($_POST['category_id']);
    $skill_name = $conn->real_escape_string($_POST['skill_name']);
    $icon_class = $conn->real_escape_string($_POST['icon_class']);

    $sql = "INSERT INTO skills (category_id, skill_name, icon_class) VALUES ($category_id, '$skill_name', '$icon_class')";
    if ($conn->query($sql) === TRUE) {
        $message = "New skill added successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Update Skill
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_skill'])) {
    $id = intval($_POST['id']);
    $category_id = intval($_POST['category_id']);
    $skill_name = $conn->real_escape_string($_POST['skill_name']);
    $icon_class = $conn->real_escape_string($_POST['icon_class']);

    $sql = "UPDATE skills SET category_id = $category_id, skill_name = '$skill_name', icon_class = '$icon_class' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Skill updated successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Delete Skill
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM skills WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Skill deleted successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM skills WHERE id = $id");
    if ($result->num_rows == 1) {
        $edit_skill = $result->fetch_assoc();
    }
}

// Fetch all skills with their category names
$skills_sql = "SELECT s.*, c.category_name 
               FROM skills s 
               JOIN skill_categories c ON s.category_id = c.id 
               ORDER BY c.category_name, s.skill_name";
$skills_result = $conn->query($skills_sql);

// Fetch all categories for the dropdown
$categories_result = $conn->query("SELECT * FROM skill_categories ORDER BY category_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Skills</h1>
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

        <h2>Skill List</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Skill Name</th>
                    <th>Icon Class (e.g., "fab fa-python")</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($skills_result && $skills_result->num_rows > 0): ?>
                    <?php while($row = $skills_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['skill_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['icon_class']); ?></td>
                            <td class="actions">
                                <a href="manage_skills.php?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="manage_skills.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No skills found. Add categories first, then add skills.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="manage_skills.php" method="POST" class="admin-form">
            <h2><?php echo $edit_skill ? 'Edit Skill' : 'Add New Skill'; ?></h2>
            
            <?php if ($edit_skill): ?>
                <input type="hidden" name="id" value="<?php echo $edit_skill['id']; ?>">
            <?php endif; ?>

            <div>
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Select a Category --</option>
                    <?php 
                    if ($categories_result && $categories_result->num_rows > 0):
                        $categories_result->data_seek(0); // Reset pointer for dropdown
                        while($cat = $categories_result->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($edit_skill) && $edit_skill['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                        </option>
                    <?php 
                        endwhile; 
                    endif;
                    ?>
                </select>
            </div>
            <div>
                <label for="skill_name">Skill Name:</label>
                <input type="text" id="skill_name" name="skill_name" value="<?php echo htmlspecialchars($edit_skill['skill_name'] ?? ''); ?>" placeholder="e.g., React" required>
            </div>
            <div>
                <label for="icon_class">Icon Class:</label>
                <small>Find icons at <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> (e.g., "fab fa-react" or "fas fa-database").</small>
                <input type="text" id="icon_class" name="icon_class" value="<?php echo htmlspecialchars($edit_skill['icon_class'] ?? ''); ?>" placeholder="e.g., fab fa-react">
            </div>
            <div>
                <?php if ($edit_skill): ?>
                    <button type="submit" name="update_skill" class="update-btn">Update Skill</button>
                    <a href="manage_skills.php" class="cancel-link">Cancel Edit</a>
                <?php else: ?>
                    <button type="submit" name="add_skill">Add Skill</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>