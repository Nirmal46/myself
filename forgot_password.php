<?php
session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the database with an expiry time
        $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $token);
        $stmt->execute();

        // Send password reset email using SMTP
        $smtp_server = 'smtp.gmail.com';
        $smtp_port = 587;
        $smtp_user = 'nirmalbasyal07@gmail.com'; // Your Gmail address
        $smtp_pass = 'your_gmail_password'; // Your Gmail App Password

        $to = $email;
        $subject = 'Password Reset Request';
        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
        $body = 'Hi, click the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a>';

        // Open a socket to the SMTP server
        $smtp = fsockopen($smtp_server, $smtp_port, $errno, $errstr, 30);

        if ($smtp) {
            fwrite($smtp, "HELO $smtp_server\r\n");
            fwrite($smtp, "AUTH LOGIN\r\n");
            fwrite($smtp, base64_encode($smtp_user) . "\r\n");
            fwrite($smtp, base64_encode($smtp_pass) . "\r\n");
            fwrite($smtp, "MAIL FROM: <$smtp_user>\r\n");
            fwrite($smtp, "RCPT TO: <$to>\r\n");
            fwrite($smtp, "DATA\r\n");
            fwrite($smtp, "From: $smtp_user\r\n");
            fwrite($smtp, "To: $to\r\n");
            fwrite($smtp, "Subject: $subject\r\n");
            fwrite($smtp, "Content-Type: text/html\r\n");
            fwrite($smtp, "\r\n");
            fwrite($smtp, $body . "\r\n");
            fwrite($smtp, ".\r\n");
            fwrite($smtp, "QUIT\r\n");

            // Close the socket
            fclose($smtp);

            echo 'This page is under maintenance. We appologise for the inconvenience  caused';
        } else {
            echo "Failed to connect to the SMTP server: $errstr ($errno)";
        }
    } else {
        echo '<script>alert("No account found with that email address")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>    
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
    
    <form action="forgot_password.php" method="POST">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
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
