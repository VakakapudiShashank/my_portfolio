<?php
/*
 * DATABASE CONNECTION
 * This file connects your site to the MySQL database.
 */

// 1. Database Credentials (Defaults for XAMPP)
$db_host = "localhost";    // Your server
$db_user = "root";         // Your username
$db_pass = "";             // Your password (default is empty)
$db_name = "portfolio_db"; // The database you created

// 2. Create Connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 3. Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 4. Fetch All General Info
// We fetch all items from `general_info` into one easy-to-use array
// so we don't have to query the database multiple times.
$info = [];
$sql = "SELECT item_key, item_value FROM general_info";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // This creates an array like: $info['bio'] = "Aspiring..."
        $info[$row['item_key']] = $row['item_value'];
    }
}

// The $conn (connection) and $info (content array) are now available
// to any file that includes this one.
?>