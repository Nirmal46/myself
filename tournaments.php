<?php
session_start();
require 'db_connection.php'; // Include the database connection

// Handle filters and sorting if applied
$filter_game = isset($_GET['game']) ? $_GET['game'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'start_date';

// Fetch tournaments with filters and sorting
$query = "SELECT * FROM tournaments WHERE name LIKE ? ORDER BY $sort_by ASC";
$stmt = $conn->prepare($query);
$search_param = "%$filter_game%";
$stmt->bind_param('s', $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments</title>
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
        <section class="tournaments">
            <h2>Upcoming Tournaments</h2>
            <form method="get" action="tournaments.php">
                <input type="text" name="game" placeholder="Search by game" value="<?php echo htmlspecialchars($filter_game); ?>">
                <select name="sort">
                    <option value="start_date" <?php echo $sort_by == 'start_date' ? 'selected' : ''; ?>>Start Date</option>
                    <option value="prize_pool" <?php echo $sort_by == 'prize_pool' ? 'selected' : ''; ?>>Prize Pool</option>
                </select>
                <button type="submit">Filter/Sort</button>
            </form>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Start Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($row['start_date'])); ?></p>
                        <p><strong>Entry Fee:</strong> $<?php echo number_format($row['entry_fee'], 2); ?></p>
                        <p><strong>Prize Pool:</strong> $<?php echo number_format($row['prize_pool'], 2); ?></p>
                        <a href="tournament_details.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                    </li>
                    <?php endwhile; ?>
            </ul>
        </section>
    </main>
    
    <!-- Footer (same as index.php) -->
    <footer>
        <ul>
            <li><a href="privacy-policy.php">Privacy Policy</a></li>
            <li><a href="terms-of-service.php">Terms of Service</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </footer>
</body>
</html>
