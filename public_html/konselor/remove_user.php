<?php
session_start();
include '../config/config.php';

// Pastikan hanya konselor yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor') {
    echo "Akses ditolak!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['klien_id'])) {
    $klien_id = filter_var($_POST['klien_id'], FILTER_SANITIZE_NUMBER_INT);

    // Update is_online menjadi 0 untuk klien yang dipilih
    $sql_update_online = "UPDATE users SET is_online = 0 WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update_online);
    $stmt_update->bind_param("i", $klien_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        echo "Klien berhasil dihapus dari daftar online.";
    } else {
        echo "Gagal menghapus klien dari daftar online.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
