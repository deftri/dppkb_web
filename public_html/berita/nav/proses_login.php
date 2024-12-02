<?php
session_start();
include 'config/config.php'; // Pastikan Anda memiliki file konfigurasi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan Password harus diisi.";
        header("Location: login.php");
        exit();
    }

    // Cari pengguna di database
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password (gunakan hashing di aplikasi nyata)
        if ($password === $user['password']) { // Gantilah dengan password_verify jika menggunakan hashing
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] == 'klien') {
                header("Location: klien/dashboard-klien.php");
            } elseif ($user['role'] == 'konselor') {
                header("Location: konselor/dashboard-konselor.php");
            } elseif ($user['role'] == 'psikolog') {
                header("Location: psikolog/dashboard-psikolog.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan.";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
