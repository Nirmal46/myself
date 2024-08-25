<?php
session_start();
require 'db_connection.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user profile information
$query = "SELECT username, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user wallet balance
$query = "SELECT balance FROM wallets WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$balance = $wallet ? $wallet['balance'] : 0;

// Handle profile picture update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($file['name']);
    $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
    
    // Check if file is an image
    if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        // Move the file to the uploads directory
        if (move_uploaded_file($file['tmp_name'], $upload_file)) {
            // Update the user's profile picture in the database
            $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", basename($file['name']), $user_id);
            $stmt->execute();
            
            // Reload the page to show the updated profile picture
            header("Location: profile.php");
            exit();
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Invalid file type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navv.css">
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
        <!-- Profile Section -->
        <section class="profile">
            <div class="profile-picture">
                <?php if ($user['profile_picture']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="default-profile.png" alt="Default Profile Picture">
                    <?php endif; ?>
                </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Balance: $<?php echo number_format($balance, 2); ?></p> <br>
                <a href="add_funds.php" class="btn">Add Funds</a>
            </div>
            <!-- Profile Picture Update Form -->
            <section class="update-profile-picture">
                <h2>Update Profile Picture</h2>
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_picture" accept="image/*" required>
                    <button type="submit" class="btn">Update Picture</button>
                </form>
            </section>
        </section>
    </main>
    
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
