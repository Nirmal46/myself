<?php
session_start();
require 'db_connection.php'; // Include the database connection

// Fetch tournament details
$tournament_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM tournaments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $tournament_id);
$stmt->execute();
$tournament = $stmt->get_result()->fetch_assoc();

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO participants (user_id, tournament_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $tournament_id);
    if ($stmt->execute()) {
        $message = "Successfully registered for the tournament!";
    } else {
        $message = "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Details</title>
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
     
    
    <main>
        <section class="tournament-details">
            <?php if ($tournament): ?>
                <h2><?php echo htmlspecialchars($tournament['name']); ?></h2>
                <p><?php echo htmlspecialchars($tournament['description']); ?></p>
                <p><strong>Start Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($tournament['start_date'])); ?></p>
                <p><strong>Entry Fee:</strong> $<?php echo number_format($tournament['entry_fee'], 2); ?></p>
                <p><strong>Prize Pool:</strong> $<?php echo number_format($tournament['prize_pool'], 2); ?></p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" action="tournament_details.php?id=<?php echo $tournament_id; ?>">
                        <button type="submit">Register</button>
                    </form>
                    <?php if (isset($message)): ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Please <a href="login.php">login</a> to register.</p>
                <?php endif; ?>
                <?php else: ?>
                    <p>Tournament not found.</p>
                    <?php endif; ?>
                </section>
            </main>
            
            <!-- Footer (same as index.php) -->
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
        