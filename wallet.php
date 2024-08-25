<?php
session_start();
require 'db_connection.php'; // Ensure this file connects to your database

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch the current wallet balance
$query = "SELECT balance FROM wallets WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$current_balance = $wallet ? $wallet['balance'] : 0;

// Handle adding funds
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0) {
        // Add funds to wallet
        $query = "UPDATE wallets SET balance = balance + ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('di', $amount, $user_id);
        $stmt->execute();

        // Record transaction
        $query = "INSERT INTO transactions (user_id, amount, description, date) VALUES (?, ?, 'Added Funds', NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('id', $user_id, $amount);
        $stmt->execute();

        header('Location: wallet.php'); // Refresh the page to show updated balance
        exit();
    } else {
        echo "Invalid amount.";
    }
}

// Fetch transaction history
$query = "SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="navv.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>  
    <header>
        <nav class="navv">
            <div class="logo">
                <a href="#">
                    <img src="untitled.png" alt="Logo">
                    <div class="name">eSports Nepal</div>
                </a>
            </div>
            <div class="bar">
                <div class="navv-contents">
                    <div class="texts"><a href="index.php"><i class="fas fa-home" style="color: #ff6347;"></i> Home</a></div>
    
                    
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Dropdown for Services -->
                        <div class="texts dropdown">
                            <a href="#"><i class="fas fa-concierge-bell" style="color: #ffcc00;"></i> Earn via Game</a>
                            <ul class="dropdown-content">
                                <li><a href="#"><i class="fas fa-plus-circle" style="color: #00ffcc;"></i> Create</a></li>
                                <li><a href="#"><i class="fas fa-search" style="color: #ffcc00;"></i> Join</a></li>
                            </ul>
                        </div>
                        <div class="texts"><a href="profile.php"><i class="fas fa-user" style="color: #4caf50;"></i> Profile</a></div>
                        <div class="texts"><a href="logout.php"><i class="fas fa-sign-out-alt" style="color: #ff4c4c;"></i> Logout</a></div>
                        <?php else: ?>
                            <div class="texts"><a href="login.php"><i class="fas fa-sign-in-alt" style="color: #4caf50;"></i> Login</a></div>
                            <div class="texts"><a href="register.php"><i class="fas fa-user-plus" style="color: #4caf50;"></i> Sign Up</a></div>
                            <?php endif; ?>
                            <div class="texts"><a href="about.php"><i class="fas fa-info-circle" style="color: #00ffcc;"></i> About</a></div>
                </div>
            </div>
        </nav>    
    </header>
     
    <!-- Footer -->
    
    <main>
        <section class="wallet">
            <h1>Your Wallet</h1>
            <p>Current Balance: $<?php echo number_format($current_balance, 2); ?></p>
            
            <h2>Transaction History</h2>
            <?php if ($transactions->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $transactions->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('F j, Y, g:i a', strtotime($transaction['date'])); ?></td>
                                <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>No transactions found.</p>
                        <?php endif; ?>
                    </section>
                </main>
                
                <footer>
                    <!-- Include your footer here -->
                </footer>
                <footer>
                    <ul>
                        <li><a href="privacy-policy.php">Privacy Policy</a></li>
                        <li><a href="terms-of-service.php">Terms of Service</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </footer>
</body>
</html>
