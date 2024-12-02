<?php
// hapus_pengguna.php
session_start();
include '../config/config.php';
include 'log_aktivitas.php';

// Cek apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Cek parameter ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID pengguna tidak valid.";
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = (int)$_GET['id'];

// Hindari penghapusan diri sendiri
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Anda tidak dapat menghapus akun Anda sendiri.";
    header("Location: admin_dashboard.php");
    exit();
}

// Ambil data pengguna untuk log aktivitas
$sql = "SELECT nama, email FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Pengguna tidak ditemukan.";
    header("Location: admin_dashboard.php");
    exit();
}
$user = $result->fetch_assoc();

// Hapus pengguna
$sql_delete = "DELETE FROM users WHERE id = $user_id";
if ($conn->query($sql_delete) === TRUE) {
    // Log aktivitas
    logActivity($conn, $_SESSION['user_id'], "Menghapus pengguna: " . $user['nama'] . " (" . $user['email'] . ")");

    $_SESSION['message'] = "Pengguna berhasil dihapus!";
    header("Location: admin_dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Error: " . $conn->error;
    header("Location: admin_dashboard.php");
    exit();
}
?>
