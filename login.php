<?php
session_start();
require 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check credentials
    $query = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

    
    <!-- Header (same as index.php) -->

    <main>
        <section class="login">
            <h2>Login</h2>
            <form method="post" action="login.php">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
                <?php if (isset($error)): ?>
                    <p><?php echo htmlspecialchars($error); ?> <a href="forgot_password.php">Forgot Password?</a></p>
                    <?php endif; ?>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
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
    <!-- Footer (same as index.php) -->
</body>
</html>
