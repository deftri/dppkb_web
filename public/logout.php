<?php
session_start();
include '../config/config.php'; // Path ke konfigurasi database

// Set is_online menjadi 0 untuk menandakan konselor offline
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'konselor') {
    $sql = "UPDATE users SET is_online = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}

// Hapus semua data sesi dan logout
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
