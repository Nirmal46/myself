<?php
session_start();
require 'db_connection.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['new_profile_picture'])) {
    $file = $_FILES['new_profile_picture'];

    // Check if file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $file['tmp_name'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileType = $file['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Define allowed file types and maximum file size
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($fileExtension, $allowedExts) && $fileSize <= $maxFileSize) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $destPath = $uploadFileDir . $newFileName;

            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Update profile picture in the database
                $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("si", $newFileName, $user_id);
                    if ($stmt->execute()) {
                        // Redirect to profile page
                        header("Location: profile.php");
                        exit();
                    } else {
                        echo "Error updating the database.";
                    }
                } else {
                    echo "Error preparing the SQL statement.";
                }
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Invalid file type or file too large.";
        }
    } else {
        echo "Error uploading file. Error code: " . $file['error'];
    }
} else {
    echo "No file uploaded.";
}
?>
