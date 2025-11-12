<?php
include('db_connect.php');
include('auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <div>
                <a href="../index.php" target="_blank" class="view-site-btn">View Site</a>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
        <p>From here you can manage all the content on your portfolio website.</p>
        
        <nav class="dashboard-nav">
            <div class="nav-card">
                <h2>Page Info</h2>
                <p>Edit general info, bio, and upload your 'About Me' image.</p>
                <a href="manage_info.php" class="btn">Manage Info</a>
            </div>
            
            <div class="nav-card">
                <h2>Projects</h2>
                <p>Add, edit, or delete portfolio projects and upload images.</p>
                <a href="manage_projects.php" class="btn">Manage Projects</a>
            </div>

            <div class="nav-card">
                <h2>Skill Categories</h2>
                <p>Add, edit, or delete skill categories (e.g., "Frontend").</p>
                <a href="manage_categories.php" class="btn">Manage Categories</a>
            </div>
            
            <div class="nav-card">
                <h2>Skills</h2>
                <p>Manage individual skills and assign them to categories.</p>
                <a href="manage_skills.php" class="btn">Manage Skills</a>
            </div>
            
            <div class="nav-card">
                <h2>Social Links</h2>
                <p>Add, edit, or delete social media, email, and other links.</p>
                <a href="manage_socials.php" class="btn">Manage Socials</a>
            </div>

            <div class="nav-card">
                <h2>Manage Admins</h2>
                <p>Add, edit, or delete admin user accounts.</p>
                <a href="manage_admins.php" class="btn">Manage Admins</a>
            </div>
        </nav>
    </div>
</body>
</html>