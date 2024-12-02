<?php
session_start();  // Start the session

include '../config/config.php';  // Include database connection

// Check if the user is logged in and has a role
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    echo "User not logged in or no role set.";
    exit();
}

$user_role = $_SESSION['role'];  // Get user role from session
$user_id = $_SESSION['user'];    // Get user ID from session

// Check if comment ID is passed via URL
if (isset($_GET['komentar_id'])) {
    $komentar_id = $_GET['komentar_id'];

    // Fetch the comment from the database
    $sql_komentar = "SELECT * FROM komentar WHERE id = '$komentar_id'";
    $result_komentar = $conn->query($sql_komentar);

    if ($result_komentar->num_rows > 0) {
        $komentar = $result_komentar->fetch_assoc();

        // Check if user is admin
        if ($user_role == 'admin') {
            // Delete the comment
            $sql_delete = "DELETE FROM komentar WHERE id = '$komentar_id'";
            if ($conn->query($sql_delete) === TRUE) {
                echo "Komentar berhasil dihapus.";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Anda tidak memiliki izin untuk menghapus komentar ini.";
        }
    } else {
        echo "Komentar tidak ditemukan.";
    }
} else {
    echo "ID komentar tidak disediakan.";
}

$conn->close();
?>
