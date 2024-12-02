<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Ambil ID pengguna dari parameter URL
$user_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : null;

if (!$user_id) {
    header("Location: admin-dashboard.php");
    exit();
}

// Ambil data pengguna berdasarkan ID
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();

if ($user_result->num_rows === 0) {
    // Jika pengguna tidak ditemukan
    header("Location: admin-dashboard.php");
    exit();
}

$user = $user_result->fetch_assoc();

// Proses update data pengguna
$update_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_id = $_POST['id'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Cek apakah password diubah, jika diubah maka hash password
    $password_hash = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password_hash'];

    // Cek apakah ID baru sudah ada di database
    $sql_check_id = "SELECT id FROM users WHERE id = ?";
    $stmt_check_id = $conn->prepare($sql_check_id);
    $stmt_check_id->bind_param("i", $new_id);
    $stmt_check_id->execute();
    $check_id_result = $stmt_check_id->get_result();

    if ($check_id_result->num_rows > 0 && $new_id != $user_id) {
        $update_error = "ID yang dimasukkan sudah digunakan oleh pengguna lain.";
    } else {
        // Proses update data pengguna
        $sql_update = "
            UPDATE users
            SET id = ?, username = ?, nama = ?, nomor_hp = ?, email = ?, role = ?, password_hash = ?
            WHERE id = ?
        ";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("issssssi", $new_id, $username, $nama, $nomor_hp, $email, $role, $password_hash, $user_id);
        
        if ($stmt_update->execute()) {
            header("Location: admin-dashboard.php");
            exit();
        } else {
            $update_error = "Terjadi kesalahan saat memperbarui data.";
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
    <h2 class="text-center mb-4">Edit Data Pengguna</h2>

    <!-- Tampilkan error jika ada -->
    <?php if ($update_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($update_error) ?></div>
    <?php endif; ?>

    <!-- Form Edit Pengguna -->
    <form action="" method="POST">
        <!-- ID Pengguna -->
        <div class="mb-3">
            <label for="id" class="form-label">ID Pengguna</label>
            <input type="number" class="form-control" id="id" name="id" value="<?= htmlspecialchars($user['id']) ?>" required>
        </div>

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
                <option value="klien" <?= $user['role'] == 'klien' ? 'selected' : '' ?>>Klien</option>
                <option value="konselor" <?= $user['role'] == 'konselor' ? 'selected' : '' ?>>Konselor</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti password">
        </div>

        <!-- Button Submit -->
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <!-- Button Kembali -->
    <a href="admin-dashboard.php" class="btn btn-secondary mt-3 w-100">Kembali ke Dashboard</a>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
