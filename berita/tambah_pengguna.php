<?php
session_start();
include '../config/config.php'; // Pastikan path ke config sudah benar

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
    header("Location: admin_dashboard.php");
    exit();
}

// Membuat token CSRF jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Menampilkan pesan sukses atau error
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}

// Proses pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cek CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Token CSRF tidak valid.";
    } else {
        // Ambil dan sanitasi data form
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = trim($_POST['role']);
        $username = ''; // Anda bisa menambahkan kolom ini jika perlu
        $nomor_hp = ''; // Anda bisa menambahkan kolom ini jika perlu
        $id_wilayah = isset($_POST['id_wilayah']) ? $_POST['id_wilayah'] : NULL;
        $session = 0;
        $ratting = 0;
        $is_verified = 0;
        $reset_token = NULL;
        $token_expiry = date("Y-m-d H:i:s");
        $is_online = 0;
        $created_at = date("Y-m-d H:i:s");

        // Validasi input form
        if (empty($nama) || empty($email) || empty($password) || empty($role)) {
            $_SESSION['error'] = "Semua field harus diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format email tidak valid.";
        } elseif (!in_array($role, ['admin', 'user'])) {
            $_SESSION['error'] = "Peran yang dipilih tidak valid.";
        } else {
            // Cek apakah ID wilayah valid
            if ($id_wilayah !== NULL) {
                $stmt = $conn->prepare("SELECT id FROM wilayah WHERE id = ?");
                $stmt->bind_param("i", $id_wilayah);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows === 0) {
                    $_SESSION['error'] = "ID wilayah yang dipilih tidak valid.";
                    $stmt->close();
                    return;
                }
                $stmt->close();
            }

            // Cek apakah email sudah digunakan
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['error'] = "Email sudah digunakan oleh pengguna lain.";
            } else {
                // Hash password sebelum disimpan
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert pengguna baru ke database
                $insert_stmt = $conn->prepare("INSERT INTO users (username, nama, nomor_hp, id_wilayah, password_hash, role, session, ratting, is_verified, reset_token, token_expiry, is_online, email, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("sssisssisissss", $username, $nama, $nomor_hp, $id_wilayah, $hashed_password, $role, $session, $ratting, $is_verified, $reset_token, $token_expiry, $is_online, $email, $created_at);
                if ($insert_stmt->execute()) {
                    $_SESSION['message'] = "Pengguna berhasil ditambahkan.";
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Gagal menambahkan pengguna: " . htmlspecialchars($insert_stmt->error);
                }
                $insert_stmt->close();
            }
            $stmt->close();
        }
    }
}

// Mendapatkan opsi wilayah dari database
$wilayah_query = "SELECT id, nama_wilayah FROM wilayah";
$wilayah_result = $conn->query($wilayah_query);
$wilayah_options = [];
if ($wilayah_result && $wilayah_result->num_rows > 0) {
    while ($row = $wilayah_result->fetch_assoc()) {
        $wilayah_options[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna Baru</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Pengguna Baru</h1>
        <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
        
        <!-- Menampilkan pesan sukses atau error -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Peran</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_wilayah">Wilayah</label>
                <select class="form-control" id="id_wilayah" name="id_wilayah">
                    <option value="">-- Pilih Wilayah --</option>
                    <?php foreach ($wilayah_options as $wilayah): ?>
                        <option value="<?php echo $wilayah['id']; ?>"><?php echo $wilayah['nama_wilayah']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
        </form>
    </div>
</body>
</html>
