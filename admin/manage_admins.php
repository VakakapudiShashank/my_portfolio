<?php
include('db_connect.php');
include('auth_check.php');

$message = '';
$edit_admin = null;

// Handle Add Admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Check if username already exists
    $check = $conn->query("SELECT id FROM admin_users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $message = "Error: This username already exists.";
    } else if (empty($password)) {
        $message = "Error: Password cannot be empty.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admin_users (username, password) VALUES ('$username', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            $message = "New admin user added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle Update Admin (Password Change)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    $id = intval($_POST['id']);
    $password = $_POST['password'];

    // Can't update if password is blank
    if (empty($password)) {
        $message = "Error: Password cannot be empty.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE admin_users SET password = '$hashed_password' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $message = "Admin password updated successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle Delete Admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Safety check: Do not delete your own account
    $self_check = $conn->query("SELECT username FROM admin_users WHERE id = $id")->fetch_assoc();
    if ($self_check['username'] == $_SESSION['admin_username']) {
        $message = "Error: You cannot delete your own account.";
    } else {
        $sql = "DELETE FROM admin_users WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $message = "Admin user deleted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle Edit Request (to fetch username for the form)
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT id, username FROM admin_users WHERE id = $id");
    if ($result->num_rows == 1) {
        $edit_admin = $result->fetch_assoc();
    }
}

// Fetch all admins
$admins_result = $conn->query("SELECT id, username FROM admin_users ORDER BY username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Admins</h1>
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

        <h2>Admin Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($admins_result->num_rows > 0): ?>
                    <?php while($row = $admins_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="actions">
                                <a href="manage_admins.php?edit=<?php echo $row['id']; ?>" class="edit">Change Password</a>
                                <?php if ($row['username'] != $_SESSION['admin_username']): // Can't delete self ?>
                                    <a href="manage_admins.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2">No admins found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="manage_admins.php" method="POST" class="admin-form">
            <h2><?php echo $edit_admin ? 'Update Admin Password' : 'Add New Admin'; ?></h2>
            
            <?php if ($edit_admin): ?>
                <input type="hidden" name="id" value="<?php echo $edit_admin['id']; ?>">
                <div>
                    <label>Username:</label>
                    <input type="text" value="<?php echo htmlspecialchars($edit_admin['username']); ?>" disabled>
                </div>
                <div>
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="update_admin" class="update-btn">Update Password</button>
                <a href="manage_admins.php" class="cancel-link">Cancel Edit</a>
            <?php else: ?>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="add_admin">Add Admin</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>