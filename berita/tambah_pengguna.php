<?php
session_start();
include '../config/config.php';

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
        $username = trim($_POST['username']);
        $nama = trim($_POST['nama']);
        $nomor_hp = trim($_POST['nomor_hp']);
        $email = trim($_POST['email']);
        $sub_role = isset($_POST['sub_role']) ? trim($_POST['sub_role']) : ''; // Ambil sub_role, jika kosong set default
        $password = $_POST['password'];

        // Pastikan sub_role tidak kosong
        if (empty($sub_role)) {
            $_SESSION['error'] = "Sub Role harus dipilih!";
            header("Location: tambah_pengguna.php");
            exit();
        }

        // Tentukan role berdasarkan sub_role
        switch ($sub_role) {
            case 'Dewasa':
                $role = 'konselor';
                break;
            case 'Sebaya':
                $role = 'konselor';
                break;
            case 'Psikolog':
                $role = 'psikolog';
                break;
            case 'Admin':
                $role = 'admin';
                break;
            case 'Klien':
                $role = 'klien';
                break;
            default:
                $role = '';
                break;
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Validasi input
        if (empty($username) || empty($nama) || empty($nomor_hp) || empty($email) || empty($password) || empty($role)) {
            $_SESSION['error'] = "Semua field harus diisi!";
            header("Location: tambah_pengguna.php");
            exit();
        }

        // Query untuk memasukkan data ke database
        $sql_insert_user = "INSERT INTO users (username, nama, nomor_hp, email, password_hash, sub_role, role, id_wilayah, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("ssssssss", $username, $nama, $nomor_hp, $email, $password_hash, $sub_role, $role, $id_wilayah);

        if ($stmt_insert_user->execute()) {
            $_SESSION['message'] = "Pengguna berhasil ditambahkan!";
            header("Location: admin_dashboard.php");
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menambahkan pengguna!";
            header("Location: tambah_pengguna.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
</head>
<body>
    <h2>Tambah Pengguna Baru</h2>

    <!-- Menampilkan pesan error atau sukses -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <!-- Form untuk menambah pengguna -->
    <form method="POST" action="tambah_pengguna.php">
        <!-- CSRF token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" required><br>

        <label for="nomor_hp">Nomor HP:</label>
        <input type="text" name="nomor_hp" id="nomor_hp" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="sub_role">Sub Role:</label>
        <select name="sub_role" id="sub_role" required>
            <option value="">Pilih Sub Role</option>
            <option value="Dewasa">Dewasa</option>
            <option value="Sebaya">Sebaya</option>
            <option value="Psikolog">Psikolog</option>
            <option value="Admin">Admin</option>
            <option value="Klien">Klien</option>
        </select><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="id_wilayah">Wilayah:</label>
        <select name="id_wilayah" id="id_wilayah" required>
            <!-- Pilihan wilayah akan diambil dari database -->
            <option value="1">Wilayah 1</option>
            <option value="2">Wilayah 2</option>
            <option value="3">Wilayah 3</option>
        </select><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
