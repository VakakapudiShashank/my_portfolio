<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$edit_project = null;
$upload_dir = '../uploads/'; // Directory for uploads, relative to this admin file

// Helper function to delete an old image
function delete_image($filename) {
    global $upload_dir;
    // Make sure filename is just the name, not a full path
    $filename = basename($filename);
    if ($filename && file_exists($upload_dir . $filename)) {
        unlink($upload_dir . $filename);
    }
}

// Handle Add Project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $github_link = $conn->real_escape_string($_POST['github_link']);
    $live_link = $conn->real_escape_string($_POST['live_link']);
    $image_url = ''; // Default empty

    // Handle File Upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $filename = time() . '_' . basename($_FILES['image_file']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            $image_url = 'uploads/' . $filename; // Store path relative to *root*
        } else {
            $message = "Error: Failed to move uploaded file.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO projects (title, description, image_url, github_link, live_link) 
                VALUES ('$title', '$description', '$image_url', '$github_link', '$live_link')";
        if ($conn->query($sql) === TRUE) {
            $message = "New project added successfully!";
        } else { $message = "Error: " . $conn->error; }
    }
}

// Handle Update Project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_project'])) {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $github_link = $conn->real_escape_string($_POST['github_link']);
    $live_link = $conn->real_escape_string($_POST['live_link']);
    $image_url = $conn->real_escape_string($_POST['existing_image_url']); // Start with old image

    // Check for new file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        // Delete old image first
        delete_image($image_url);
        
        // Upload new image
        $filename = time() . '_' . basename($_FILES['image_file']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            $image_url = 'uploads/' . $filename; // Set new image path
        } else {
            $message = "Error: Failed to move uploaded file.";
        }
    }

    if (empty($message)) {
        $sql = "UPDATE projects SET 
                title = '$title', description = '$description', image_url = '$image_url', 
                github_link = '$github_link', live_link = '$live_link' 
                WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $message = "Project updated successfully!";
        } else { $message = "Error: " . $conn->error; }
    }
}

// Handle Delete Project
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // First, find the image to delete it
    $result = $conn->query("SELECT image_url FROM projects WHERE id = $id");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        delete_image($row['image_url']);
    }
    
    // Then, delete the database row
    $sql = "DELETE FROM projects WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Project deleted successfully!";
    } else { $message = "Error: " . $conn->error; }
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM projects WHERE id = $id");
    if ($result->num_rows == 1) {
        $edit_project = $result->fetch_assoc();
    }
}

// Fetch all projects
$projects_result = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Projects</h1>
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

        <h2>Project List</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Links</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($projects_result->num_rows > 0): ?>
                    <?php while($row = $projects_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if ($row['image_url']): ?>
                                    <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="table-preview-img">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo substr(htmlspecialchars($row['description']), 0, 70); ?>...</td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row['github_link']); ?>" target="_blank">GitHub</a><br>
                                <a href="<?php echo htmlspecialchars($row['live_link']); ?>" target="_blank">Live</a>
                            </td>
                            <td class="actions">
                                <a href="manage_projects.php?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="manage_projects.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No projects found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="manage_projects.php" method="POST" class="admin-form" enctype="multipart/form-data">
            <h2><?php echo $edit_project ? 'Edit Project' : 'Add New Project'; ?></h2>
            
            <?php if ($edit_project): ?>
                <input type="hidden" name="id" value="<?php echo $edit_project['id']; ?>">
                <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_project['image_url']); ?>">
            <?php endif; ?>

            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_project['title'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($edit_project['description'] ?? ''); ?></textarea>
            </div>
            <div>
                <label for="image_file">Project Image:</label>
                <?php if ($edit_project && $edit_project['image_url']): ?>
                    <p>Current image:</p>
                    <img src="../<?php echo htmlspecialchars($edit_project['image_url']); ?>" alt="Current Image" class="form-preview-img">
                    <p style="margin-top: 10px;">Upload new file to replace:</p>
                <?php endif; ?>
                <input type="file" id="image_file" name="image_file" accept="image/png, image/jpeg, image/jpg, image/gif, image/avif, image/webp">
            </div>
            <div>
                <label for="github_link">GitHub Link:</label>
                <input type="text" id="github_link" name="github_link" value="<?php echo htmlspecialchars($edit_project['github_link'] ?? ''); ?>" placeholder="https://github.com/...">
            </div>
            <div>
                <label for="live_link">Live Demo Link:</label>
                <input type="text" id="live_link" name="live_link" value="<?php echo htmlspecialchars($edit_project['live_link'] ?? ''); ?>" placeholder="https://example.com...">
            </div>
            <div>
                <?php if ($edit_project): ?>
                    <button type="submit" name="update_project" class="update-btn">Update Project</button>
                    <a href="manage_projects.php" class="cancel-link">Cancel Edit</a>
                <?php else: ?>
                    <button type="submit" name="add_project">Add Project</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>