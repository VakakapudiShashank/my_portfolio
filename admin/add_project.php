<?php
// 1. Connect to the database
include('db_connect.php');

// 2. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Get the data from the form.
    // We use real_escape_string to sanitize the data and prevent SQL injection.
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $github_link = $conn->real_escape_string($_POST['github_link']);
    $live_link = $conn->real_escape_string($_POST['live_link']);

    // 4. Create the SQL INSERT query
    $sql = "INSERT INTO projects (title, description, image_url, github_link, live_link) 
            VALUES ('$title', '$description', '$image_url', '$github_link', '$live_link')";

    // 5. Execute the query
    if ($conn->query($sql) === TRUE) {
        // If successful, redirect back to the admin page
        header("Location: index.php?status=success");
        exit;
    } else {
        // If there was an error, show the error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

} else {
    // If someone tries to access this file directly, redirect them
    header("Location: index.php");
    exit;
}

// 6. Close the connection
$conn->close();
?>