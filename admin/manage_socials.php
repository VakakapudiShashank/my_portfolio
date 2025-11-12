<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$edit_link = null;

// Handle Add Link
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_link'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $url = $conn->real_escape_string($_POST['url']);
    $icon_class = $conn->real_escape_string($_POST['icon_class']);
    $location = $conn->real_escape_string($_POST['location']);

    $sql = "INSERT INTO social_links (name, url, icon_class, location) VALUES ('$name', '$url', '$icon_class', '$location')";
    if ($conn->query($sql) === TRUE) {
        $message = "New link added successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Update Link
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_link'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $url = $conn->real_escape_string($_POST['url']);
    $icon_class = $conn->real_escape_string($_POST['icon_class']);
    $location = $conn->real_escape_string($_POST['location']);

    $sql = "UPDATE social_links SET name = '$name', url = '$url', icon_class = '$icon_class', location = '$location' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Link updated successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Delete Link
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM social_links WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Link deleted successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM social_links WHERE id = $id");
    if ($result->num_rows == 1) {
        $edit_link = $result->fetch_assoc();
    }
}

// Fetch all links
$links_result = $conn->query("SELECT * FROM social_links ORDER BY location, id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Social Links</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Social Links</h1>
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

        <h2>Social Links List</h2>
        <table>
            <thead>
                <tr>
                    <th>Name (e.g., GitHub)</th>
                    <th>URL</th>
                    <th>Icon Class (e.g., "fab fa-github")</th>
                    <th>Location (hero, contact, footer)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($links_result && $links_result->num_rows > 0): ?>
                    <?php while($row = $links_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['url']); ?></td>
                            <td><?php echo htmlspecialchars($row['icon_class']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td class="actions">
                                <a href="manage_socials.php?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="manage_socials.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No links found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="manage_socials.php" method="POST" class="admin-form">
            <h2><?php echo $edit_link ? 'Edit Link' : 'Add New Link'; ?></h2>
            
            <?php if ($edit_link): ?>
                <input type="hidden" name="id" value="<?php echo $edit_link['id']; ?>">
            <?php endif; ?>

            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_link['name'] ?? ''); ?>" placeholder="e.g., Email" required>
            </div>
            <div>
                <label for="url">URL:</label>
                <input type="text" id="url" name="url" value="<?php echo htmlspecialchars($edit_link['url'] ?? ''); ?>" placeholder="e.g., mailto:me@example.com" required>
                <small>Use `mailto:your@email.com` for email or `https://wa.me/123...` for WhatsApp.</small>
            </div>
            <div>
                <label for="icon_class">Icon Class:</label>
                <small>Find icons at <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> (e.g., "fas fa-envelope").</small>
                <input type="text" id="icon_class" name="icon_class" value="<?php echo htmlspecialchars($edit_link['icon_class'] ?? ''); ?>" placeholder="e.g., fas fa-envelope">
            </div>
            <div>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($edit_link['location'] ?? ''); ?>" placeholder="e.g., hero, contact, or footer" required>
            </div>
            <div>
                <?php if ($edit_link): ?>
                    <button type="submit" name="update_link" class="update-btn">Update Link</button>
                    <a href="manage_socials.php" class="cancel-link">Cancel Edit</a>
                <?php else: ?>
                    <button type="submit" name="add_link">Add Link</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>