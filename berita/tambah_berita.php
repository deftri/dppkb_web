<?php
// Aktifkan error reporting untuk debugging jika di lingkungan pengembangan
if ($_SERVER['ENV'] == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

session_start();
include("../config/config.php"); // Pastikan path ke config sudah benar

// Membuat token CSRF jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inisialisasi variabel pesan
$message = '';
$error = '';

// Memproses data jika ada pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Permintaan tidak valid.";
    } else {
        // Mengambil dan menyaring data input
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Validasi input
        if (empty($username) || empty($password)) {
            $error = "Silakan isi semua field.";
        } else {
            // Menggunakan prepared statements untuk mencegah SQL Injection
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    $admin = $result->fetch_assoc();
                    // Memeriksa password menggunakan password_verify
                    if (password_verify($password, $admin['password_hash'])) {
                        // Mengatur session
                        $_SESSION['admin'] = $admin['username'];
                        $_SESSION['role'] = 'admin'; // Tambahkan ini
                        // Regenerate session ID untuk mencegah session fixation
                        session_regenerate_id(true);
                        header("Location: admin_dashboard.php");
                        exit();
                    } else {
                        $error = "Username atau password salah.";
                    }
                } else {
                    $error = "Username atau password salah.";
                }

                $stmt->close();
            } else {
                // Jika prepared statement gagal
                $error = "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
                error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
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
        .alert {
            margin-top: 20px;
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

<div class="login-container">
    <h2 class="text-center">Login Admin</h2>

    <!-- Menampilkan pesan sukses atau error -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <footer>
        <p>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>
        <a href="privacy_policy.php">Privacy Policy</a> | 
        <a href="terms_of_service.php">Terms of Service</a>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>
