<?php
session_start();
require 'db_connection.php'; // Ensure this file connects to your database

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];
    
    // Validate the amount
    if (is_numeric($amount) && $amount > 0) {
        // Update the wallet balance
        $query = "UPDATE wallets SET balance = balance + ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $amount, $user_id);
        
        if ($stmt->execute()) {
            // Insert transaction record
            $query = "INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, 'Funds added')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("id", $user_id, $amount);
            $stmt->execute();

            // Redirect to the wallet page
            header('Location: wallet.php');
            exit();
        } else {
            $message = "Failed to add funds.";
        }
    } else {
        $message = "Invalid amount.";
    }
}


// Fetch the current wallet balance
$query = "SELECT balance FROM wallets WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$current_balance = $wallet ? $wallet['balance'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Funds</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style></style>
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
                <li class="dropdown">
                    <a href="#"><i class="fas fa-concierge-bell" style="color: #ffcc00;"></i> Services</a>
                    <ul class="dropdown-content">
                        <li><a href="#"><i class="fas fa-plus-circle" style="color: #00ffcc;"></i> Create</a></li>
                        <li><a href="#"><i class="fas fa-search" style="color: #ffcc00;"></i> Join</a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="fas fa-envelope" style="color: #cc66ff;"></i> Contact</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle" style="color: #00ffcc;"></i> About</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php"><i class="fas fa-user" style="color: #4caf50;"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="color: #ff4c4c;"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt" style="color: #4caf50;"></i> Login</a></li>
                    <li><a href="register.php"><i class="fas fa-user-plus" style="color: #4caf50;"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>    
    </header>

    <main>
        <section class="add-funds">
            <h1>Add Funds to Your Wallet</h1>
            <?php if (isset($message)) : ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <p>Current Balance: $<?php echo number_format($current_balance, 2); ?></p>
            <form action="add_funds.php" method="post">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                <button type="submit">Add Funds</button>
            </form>
        </section>
    </main>

    <footer>
        <!-- Include your footer here -->
    </footer>
</body>
</html>
