<?php
session_start();
require 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file upload
    $profilePicture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePicture = $newFileName;
            }
        }
    }

    // Insert user into database
    $query = "INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $password, $profilePicture);
    $stmt->execute();
    
    // Start session and redirect user
    $_SESSION['user_id'] = $stmt->insert_id;
    header("Location: profile.php");
    exit();
}
?>
