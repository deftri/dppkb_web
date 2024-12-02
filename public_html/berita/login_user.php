<?php
session_start();
include('../config/config.php'); // Koneksi ke database

// Mencatat statistik kunjungan halaman
$halaman = basename($_SERVER['PHP_SELF']);
$tanggal = date("Y-m-d");

$sql_statistik = "INSERT INTO statistik (halaman, kunjungan, tanggal) 
                  VALUES ('$halaman', 1, '$tanggal') 
                  ON DUPLICATE KEY UPDATE kunjungan = kunjungan + 1";
$conn->query($sql_statistik);

// Proses login jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Menambahkan pengecekan untuk username admin dengan password khusus
    if ($username == 'admin' && $password == '29122003') {
        $_SESSION['admin'] = 'admin'; // Set session untuk admin
        header("Location: admin_dashboard.php");
        exit();
    }

    // Jika bukan admin, cek ke database
    $sql = "SELECT * FROM pengguna WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Menghasilkan kode OTP dan waktu kedaluwarsa
            $otp_code = rand(100000, 999999);
            $otp_expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));
            
            // Memperbarui kode OTP di database
            $conn->query("UPDATE pengguna SET otp_code='$otp_code', otp_expires='$otp_expires' WHERE id='{$user['id']}'");

            // Mengirim kode OTP ke email pengguna
            mail($user['email'], "Kode OTP Anda", "Kode OTP Anda adalah: $otp_code");

            // Simpan session username dan role, lalu arahkan ke verifikasi OTP
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: verifikasi_otp.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Password salah.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Username tidak ditemukan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2 {
            margin-bottom: 30px;
            color: #4e5d6c;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #4e5d6c;
            border-color: #4e5d6c;
        }
        .btn-primary:hover {
            background-color: #3b4753;
            border-color: #2f3a44;
        }
        .form-label {
            font-weight: 600;
            color: #4e5d6c;
        }
        footer {
            text-align: center;
            margin-top: 30px;
        }
        footer a {
            color: #007bff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Login dengan OTP</h2>
    
    <!-- Form Login -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Footer -->
    <footer>
        <a href="privacy_policy.php">Privacy Policy</a> | 
        <a href="terms_of_service.php">Terms of Service</a>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
