<?php
session_start();
include 'config.php'; // file konfigurasi database

// Mendapatkan data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk mencari pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password
    if (password_verify($password, $user['password_hash'])) {
        // Simpan data sesi pengguna
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        if ($user['role'] === 'klien') {
            header("Location: klien/dashboard-klien.php");
        } elseif ($user['role'] === 'konselor') {
            header("Location: konselor/dashboard-konselor.php");
        } elseif ($user['role'] === 'psikolog') {
            header("Location: psikolog/dashboard-psikolog.php");
        } elseif ($user['role'] === 'admin') {
            header("Location: admin/dashboard-admin.php");
        }
        exit();
    } else {
        echo "Password salah!";
    }
} else {
    echo "Username tidak ditemukan!";
}
?>
