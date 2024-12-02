<?php
session_start();
include '../config/config.php';

// Pastikan jika ada user yang sedang login, set status 'is_online' menjadi 0
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $update_sql = "UPDATE users SET is_online = 0 WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Hapus semua data sesi dan redirect ke halaman index
session_unset();
session_destroy();
header("Location: ../public_html/berita/index.php");  // Redirect ke index.php
exit();
?>
