<?php
// 1. Connect to the database
include('db_connect.php');

// 2. Fetch all projects from the database
$sql = "SELECT * FROM projects ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 20px; }
        .container { width: 90%; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1, h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .edit { background-color: #ffc107; color: #333; }
        .delete { background-color: #dc3545; color: white; }
        form { margin-top: 30px; border-top: 2px solid #eee; padding-top: 20px; }
        form div { margin-bottom: 15px; }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form input[type="text"], form textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        form button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <h2>Manage Projects</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 3. Loop through the results and display them in the table
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . substr($row['description'], 0, 50) . "...</td>"; // Show a snippet
                        echo "<td>" . $row['image_url'] . "</td>";
                        echo "<td class='actions'>
                                <a href='edit_project.php?id=" . $row['id'] . "' class='edit'>Edit</a>
                                <a href='delete_project.php?id=" . $row['id'] . "' class='delete'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No projects found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <form action="add_project.php" method="POST">
            <h2>Add New Project</h2>
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div>
                <label for="image_url">Image URL (e.g., 'project.png'):</label>
                <input type="text" id="image_url" name="image_url">
            </div>
            <div>
                <label for="github_link">GitHub Link:</label>
                <input type="text" id="github_link" name="github_link">
            </div>
            <div>
                <label for="live_link">Live Demo Link:</label>
                <input type="text" id="live_link" name="live_link">
            </div>
            <div>
                <button type="submit">Add Project</button>
            </div>
        </form>

    </div>

</body>
</html>
<?php
// 5. Close the database connection
$conn->close();
?>