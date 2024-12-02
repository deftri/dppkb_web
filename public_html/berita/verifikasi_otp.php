<?php
session_start();
include '../config/config.php'; // Koneksi ke database

// Memastikan bahwa data OTP dikirim via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil OTP yang dikirim dari form
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp_code']);
    
    // Ambil data pengguna dari database berdasarkan sesi atau parameter lain yang relevan
    $user_id = $_SESSION['user_id'] ?? null; // Sesuaikan dengan cara Anda menyimpan ID pengguna
    if ($user_id) {
        $query = "SELECT otp_code, otp_expires FROM users WHERE id = '$user_id'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifikasi OTP dan cek apakah masih berlaku
            if ($otp_code == $user['otp_code'] && strtotime($user['otp_expires']) > time()) {
                // OTP benar dan belum kadaluarsa
                $_SESSION['user_id'] = $user_id; // Set session ID pengguna
                header("Location: index.php");
                exit();
            } else {
                // OTP salah atau kedaluwarsa
                echo "<div class='alert alert-danger'>Kode OTP salah atau sudah kedaluwarsa.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Pengguna tidak ditemukan.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID pengguna tidak ditemukan dalam sesi.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Metode pengiriman tidak valid.</div>";
}

$conn->close(); // Tutup koneksi database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2; /* Latar belakang abu-abu terang */
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
            color: #4e5d6c; /* Warna biru pastel yang lembut */
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
    <h2 class="text-center">Verifikasi OTP</h2>
    
    <!-- Form OTP -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="otp_code" class="form-label">Masukkan Kode OTP</label>
            <input type="text" name="otp_code" id="otp_code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
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
