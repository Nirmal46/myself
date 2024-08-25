<?php
require 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $query = "SELECT user_id, expires_at FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reset = $result->fetch_assoc();
        $user_id = $reset['user_id'];
        $expires_at = $reset['expires_at'];

        // Check if the token has expired
        if (strtotime($expires_at) > time()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                // Update the password
                $query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $new_password, $user_id);
                $stmt->execute();

                // Delete the token from the database
                $query = "DELETE FROM password_resets WHERE token = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $token);
                $stmt->execute();

                echo "Your password has been updated.";
            }
        } else {
            echo "This link has expired.";
        }
    } else {
        echo "Invalid token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
    
    <h1>Reset Password</h1>
    <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">Enter your new password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Submit</button>
    </form>
    <footer>
        <ul>
            <li><a href="privacy-policy.php">Privacy Policy</a></li>
            <li><a href="terms-of-service.php">Terms of Service</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </footer>
</body>
</html>
