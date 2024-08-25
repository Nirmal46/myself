<?php
session_start();
require 'db_connection.php';
//-
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Admin functionality logic here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Inline CSS for Admin Panel */
        .admin-panel {
            padding: 20px;
        }

        .admin-panel h1 {
            text-align: center;
        }

        .admin-panel .section {
            margin: 20px 0;
        }

        .admin-panel .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .admin-panel .btn:hover {
            background-color: #0056b3;
        }

        .admin-panel .btn + .btn {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <a href="#">
                    <div class="imgg"><img src="zlogo.png" alt="Logo"></div>
                    <div class="name">Neer Productions</div>
                </a>
            </div>
            <div class="menu-icon" onclick="toggleMenu()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <ul id="nav-links">
                <li><a href="index.php"><i class="fas fa-home" style="color: #ff6347;"></i> Home</a></li>
                <li><a href="tournaments.php"><i class="fas fa-trophy" style="color: #00ffcc;"></i> Tournaments</a></li>
                <li><a href="profile.php"><i class="fas fa-user" style="color: #4caf50;"></i> Profile</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="color: #ff4c4c;"></i> Logout</a></li>
            </ul>
        </nav>    
    </header>

    <!-- Admin Panel -->
    <section class="admin-panel">
        <h1>Admin Panel</h1>

        <!-- Manage Tournaments -->
        <div class="section manage-tournaments">
            <h2>Manage Tournaments</h2>
            <a href="create-tournament.php" class="btn">Create Tournament</a>
            <a href="view-tournaments.php" class="btn">View Tournaments</a>
        </div>

        <!-- Manage Users -->
        <div class="section manage-users">
            <h2>Manage Users</h2>
            <a href="view-users.php" class="btn">View Users</a>
            <a href="ban-user.php" class="btn">Ban User</a>
        </div>

        <!-- Transaction Management -->
        <div class="section transaction-management">
            <h2>Transaction Management</h2>
            <a href="view-transactions.php" class="btn">View Transactions</a>
            <a href="refund-transaction.php" class="btn">Refund Transaction</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <ul>
            <li><a href="privacy-policy.php">Privacy Policy</a></li>
            <li><a href="terms-of-service.php">Terms of Service</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </footer>
</body>
</html>
