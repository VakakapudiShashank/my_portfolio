<?php
// We've already started the session in db_connect.php
// which should be included *before* this file.

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // User is not logged in. Redirect to login page.
    header("Location: login.php");
    exit;
}

// User is logged in, script can continue.
?>