<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$about_image_path = '../BG.png'; // Path relative to this admin file

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Handle Image Upload ---
    if (isset($_FILES['about_image']) && $_FILES['about_image']['error'] == 0) {
        // Basic check for image type
        $allowed_types = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
        if (in_array($_FILES['about_image']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['about_image']['tmp_name'], $about_image_path)) {
                $message = "Image uploaded successfully. ";
            } else {
                $message = "Error: Failed to move uploaded file. Check folder permissions. ";
            }
        } else {
            $message = "Error: Invalid file type. Please upload a PNG, JPG, WEBP, or GIF. ";
        }
    }
    
    // --- Handle Image Delete ---
    if (isset($_POST['delete_image']) && $_POST['delete_image'] == '1') {
        if (file_exists($about_image_path)) {
            unlink($about_image_path);
            $message .= "Image deleted successfully. ";
        } else {
            $message .= "No image to delete. ";
        }
    }

    // --- Handle Text Info Update ---
    if (isset($_POST['update_info'])) {
        foreach ($_POST as $key => $value) {
            // Only update fields that are not part of the form controls
            if ($key != 'update_info' && $key != 'delete_image') {
                $sql = "UPDATE general_info SET item_value = ? WHERE item_key = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ss", $value, $key);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        $message .= "General info updated successfully!";
    }
    
    // Re-fetch the info array to show new values
    $sql = "SELECT item_key, item_value FROM general_info";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $info = []; // Clear old info
        while($row = $result->fetch_assoc()) {
            $info[$row['item_key']] = $row['item_value'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage General Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage General Info</h1>
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

        <form action="manage_info.php" method="POST" class="admin-form" enctype="multipart/form-data">
            <input type="hidden" name="update_info" value="1">
            
            <h2>Home Section</h2>
            <div>
                <label for="name_title">Name Title:</label>
                <input type="text" id="name_title" name="name_title" value="<?php echo htmlspecialchars($info['name_title'] ?? ''); ?>">
            </div>
            <div>
                <label for="subtitle">Subtitle:</label>
                <input type="text" id="subtitle" name="subtitle" value="<?php echo htmlspecialchars($info['subtitle'] ?? ''); ?>">
            </div>
            <div>
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($info['bio'] ?? ''); ?></textarea>
            </div>
            
            <h2>About Section</h2>
            <div>
                <label for="about_intro">About Intro:</label>
                <input type="text" id="about_intro" name="about_intro" value="<?php echo htmlspecialchars($info['about_intro'] ?? ''); ?>">
            </div>
            <div>
                <label for="about_description">About Description:</label>
                <textarea id="about_description" name="about_description"><?php echo htmlspecialchars($info['about_description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label>About Me Image (BG.png)</label>
                <?php if (file_exists($about_image_path)): ?>
                    <img src="<?php echo $about_image_path; ?>?t=<?php echo time(); // Cache buster ?>" alt="Current About Image" class="form-preview-img">
                    <div style="margin-top: 5px;">
                        <input type="checkbox" id="delete_image" name="delete_image" value="1">
                        <label for="delete_image" style="display: inline; font-weight: normal; margin-left: 5px;">Delete current image</label>
                    </div>
                <?php else: ?>
                    <p>No image uploaded. (Site will show a broken image if 'BG.png' is missing).</p>
                <?php endif; ?>
                <label for="about_image" style="margin-top: 10px;">Upload/Replace Image:</label>
                <input type="file" id="about_image" name="about_image" accept="image/png, image/jpeg, image/jpg, image/webp">
                <small>This will replace the existing `BG.png` file.</small>
            </div>
            
            <h2>Contact Section</h2>
            <div>
                <label for="contact_description">Contact Description:</label>
                <textarea id="contact_description" name="contact_description"><?php echo htmlspecialchars($info['contact_description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <button type="submit" class="update-btn">Save All Changes</button>
            </div>
        </form>
    </div>
</body>
</html>