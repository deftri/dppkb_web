<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$register_error = '';
$register_success = false;

// Ambil data wilayah dari database
$sql_wilayah = "SELECT id, nama_wilayah FROM wilayah ORDER BY nama_wilayah";
$result_wilayah = $conn->query($sql_wilayah);
$wilayah_options = '';

if ($result_wilayah->num_rows > 0) {
    // Menampilkan pilihan wilayah
    while ($row = $result_wilayah->fetch_assoc()) {
        $wilayah_options .= "<option value='" . $row['id'] . "'>" . $row['nama_wilayah'] . "</option>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $sub_role = $_POST['sub_role'];
    $password = $_POST['password'];
    $id_wilayah = $_POST['id_wilayah'];

    // Hash password sebelum disimpan
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Tentukan nilai role berdasarkan sub_role
    switch ($sub_role) {
        case 'Dewasa':
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
            $register_error = "Sub Role tidak valid!";
            break;
    }

    // Validasi input
    if (empty($username) || empty($nama) || empty($nomor_hp) || empty($email) || empty($password) || empty($sub_role) || empty($id_wilayah)) {
        $register_error = "Semua field harus diisi!";
    }

    // Masukkan data ke database jika tidak ada error
    if (!$register_error) {
        $sql = "INSERT INTO users (username, nama, nomor_hp, email, password_hash, sub_role, role, id_wilayah, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $username, $nama, $nomor_hp, $email, $password_hash, $sub_role, $role, $id_wilayah);

        if ($stmt->execute()) {
            $register_success = true;
        } else {
            $register_error = "Terjadi kesalahan saat menyimpan data!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Tambah Pengguna Baru</h2>

    <?php if ($register_error): ?>
        <div class="alert alert-danger">
            <?= $register_error; ?>
        </div>
    <?php elseif ($register_success): ?>
        <div class="alert alert-success">
            Pengguna berhasil ditambahkan.
        </div>
    <?php endif; ?>

    <form method="POST" action="tambah_pengguna.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= isset($username) ? $username : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= isset($nama) ? $nama : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nomor_hp" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="<?= isset($nomor_hp) ? $nomor_hp : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="sub_role" class="form-label">Sub Role</label>
            <select class="form-select" id="sub_role" name="sub_role" required>
                <option value="Klien" <?= isset($sub_role) && $sub_role === 'Klien' ? 'selected' : ''; ?>>Klien</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="id_wilayah" class="form-label">Wilayah</label>
            <select class="form-select" id="id_wilayah" name="id_wilayah" required>
                <option value="">Pilih Wilayah</option>
                <?= $wilayah_options; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Daftar Pengguna</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
