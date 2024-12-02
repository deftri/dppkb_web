<?php
session_start();
include '../config/config.php';

// Cek apakah sesi aktif dan pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Perbarui status pengguna menjadi offline (is_online = 0)
    $update_sql = "UPDATE users SET is_online = 0 WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $user_id);

    // Pastikan query berhasil
    if ($stmt->execute()) {
        // Sukses memperbarui status, lanjutkan logout
    } else {
        // Jika gagal memperbarui status, tampilkan pesan error
        echo "<script>alert('Gagal memperbarui status online. Silakan coba lagi.'); window.location.href = '../public/dashboard_psikolog.php';</script>";
        exit();
    }
}

// Hapus semua data sesi dan redirect ke halaman login
session_unset();  // Menghapus semua data sesi
session_destroy(); // Menghancurkan sesi
header("Location: ../public/login.php"); // Redirect ke halaman login
exit(); // Pastikan tidak ada kode yang dieksekusi setelah header redirect
?>
