<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Pastikan ada ID pengguna yang diberikan di URL
if (!isset($_GET['id'])) {
    header("Location: admin-dashboard.php");
    exit();
}

// Ambil ID pengguna dari URL
$user_id = $_GET['id'];

// Ambil data pengguna dari database
$sql_get_user = "SELECT * FROM users WHERE id = ?";
$stmt_get_user = $conn->prepare($sql_get_user);
$stmt_get_user->bind_param("i", $user_id);
$stmt_get_user->execute();
$user_result = $stmt_get_user->get_result();

// Jika pengguna tidak ditemukan
if ($user_result->num_rows === 0) {
    header("Location: admin-dashboard.php");
    exit();
}

$user = $user_result->fetch_assoc();

// Proses update data pengguna
$update_error = '';
$update_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $sub_role = isset($_POST['sub_role']) ? $_POST['sub_role'] : '';  // Pastikan sub_role diset dengan nilai yang benar
    $password = $_POST['password'];

    // Validasi input yang diterima
    if (empty($username) || empty($nama) || empty($nomor_hp) || empty($email) || empty($role)) {
        $update_error = "Semua field harus diisi!";
    } else {
        // Cek apakah username sudah ada di database (kecuali untuk pengguna yang sedang diubah)
        $sql_check_username = "SELECT id FROM users WHERE username = ? AND id != ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param("si", $username, $user_id);
        $stmt_check_username->execute();
        $check_username_result = $stmt_check_username->get_result();

        if ($check_username_result->num_rows > 0) {
            $update_error = "Username yang dimasukkan sudah digunakan oleh pengguna lain.";
        } else {
            // Proses update data pengguna
            $sql_update = "
    UPDATE users
    SET username = ?, nama = ?, nomor_hp = ?, email = ?, role = ?, sub_role = ?, id_wilayah = ?
    WHERE id = ?
";

$stmt_update = $conn->prepare($sql_update);

// Parameter yang di-bind
$stmt_update->bind_param("sssssssi", $username, $nama, $nomor_hp, $email, $role, $sub_role, $id_wilayah, $id);

if ($stmt_update->execute()) {
    // Berhasil update
} else {
    // Gagal update
    echo "Terjadi kesalahan: " . $conn->error;
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
    <title>Edit Pengguna - Dashboard Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Pengguna</h2>

    <!-- Tampilkan error jika ada -->
    <?php if ($update_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($update_error) ?></div>
    <?php elseif ($update_success): ?>
        <div class="alert alert-success">Pengguna berhasil diperbarui!</div>
    <?php endif; ?>

    <!-- Form Edit Pengguna -->
    <form action="" method="POST">
        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <!-- Nama Pengguna -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
        </div>

        <!-- Nomor HP -->
        <div class="mb-3">
            <label for="nomor_hp" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="<?= htmlspecialchars($user['nomor_hp']) ?>" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="klien" <?= $user['role'] === 'klien' ? 'selected' : '' ?>>Klien</option>
                <option value="konselor" <?= $user['role'] === 'konselor' ? 'selected' : '' ?>>Konselor</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- Sub Role -->
        <div class="mb-3">
            <label for="sub_role" class="form-label">Sub Role</label>
            <select class="form-select" id="sub_role" name="sub_role">
                <option value="klien" <?= $user['sub_role'] === 'klien' ? 'selected' : '' ?>>Klien</option>
                <option value="sebaya" <?= $user['sub_role'] === 'sebaya' ? 'selected' : '' ?>>Sebaya</option>
                <option value="dewasa" <?= $user['sub_role'] === 'dewasa' ? 'selected' : '' ?>>Dewasa</option>
                <option value="admin" <?= $user['sub_role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- Password (Optional) -->
        <div class="mb-3">
            <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <!-- Button Submit -->
        <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
    </form>

    <!-- Button Kembali -->
    <a href="admin-dashboard.php" class="btn btn-secondary mt-3 w-100">Kembali ke Dashboard</a>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
