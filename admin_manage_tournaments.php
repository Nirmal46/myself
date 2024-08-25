<?php
session_start();
require 'db_connection.php'; // Include the database connection

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// CRUD operations for tournaments would go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Tournaments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header (same as index.php) -->

    <main>
        <section class="admin-panel">
            <h2>Manage Tournaments</h2>
            <!-- CRUD forms for managing tournaments -->
        </section>
    </main>

    <!-- Footer (same as index.php) -->
</body>
</html>
