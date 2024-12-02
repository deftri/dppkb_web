<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Proses tambah pengguna
$insert_error = '';
$insert_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $sub_role = isset($_POST['sub_role']) ? $_POST['sub_role'] : 'klien';  // Default ke 'klien' jika tidak ada pilihan
    $id_wilayah = $_POST['id_wilayah'];  // Ambil id_wilayah dari form
    $password = $_POST['password'];

    // Validasi input yang diterima
    if (empty($username) || empty($nama) || empty($nomor_hp) || empty($email) || empty($role) || empty($password) || empty($id_wilayah)) {
        $insert_error = "Semua field harus diisi!";
    } else {
        // Cek apakah username sudah ada di database
        $sql_check_username = "SELECT id FROM users WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $check_username_result = $stmt_check_username->get_result();

        if ($check_username_result->num_rows > 0) {
            $insert_error = "Username yang dimasukkan sudah digunakan oleh pengguna lain.";
        } else {
            // Proses insert data pengguna (ID akan otomatis di-generate oleh database)
            $sql_insert = "
                INSERT INTO users (username, nama, nomor_hp, email, role, sub_role, id_wilayah, password_hash)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ";

            // Hash password sebelum disimpan
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssssss", $username, $nama, $nomor_hp, $email, $role, $sub_role, $id_wilayah, $password_hash);

            if ($stmt_insert->execute()) {
                $insert_success = true;
                // Redirect to admin-dashboard with success message
                header("Location: admin-dashboard.php?status=success");
                exit();
            } else {
                $insert_error = "Terjadi kesalahan saat menambah pengguna: " . $conn->error;
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
    <title>Tambah Pengguna - Dashboard Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }
        .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Tambah Pengguna</h2>

    <?php if ($insert_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($insert_error); ?></div>
    <?php elseif ($insert_success): ?>
        <div class="alert alert-success">Pengguna berhasil ditambahkan!</div>
    <?php endif; ?>

    <form action="" method="POST">
        <!-- Username -->
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <!-- Nama Pengguna -->
        <div class="form-group">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <!-- Nomor HP -->
        <div class="form-group">
            <label for="nomor_hp" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Role -->
        <div class="form-group">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="klien">Klien</option>
                <option value="konselor">Konselor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Sub Role -->
        <div class="form-group">
            <label for="sub_role" class="form-label">Sub Role</label>
            <select class="form-select" id="sub_role" name="sub_role" required>
                <option value="klien">Klien</option>
                <option value="sebaya">Sebaya</option>
                <option value="dewasa">Dewasa</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Wilayah -->
        <div class="form-group">
            <label for="id_wilayah" class="form-label">Wilayah</label>
            <select class="form-select" id="id_wilayah" name="id_wilayah" required>
                <?php
                // Ambil data wilayah dari database
                $sql_wilayah = "SELECT * FROM wilayah";
                $result_wilayah = $conn->query($sql_wilayah);

                if ($result_wilayah->num_rows > 0) {
                    // Loop untuk menampilkan wilayah
                    while ($row = $result_wilayah->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nama_wilayah']) . "</option>";
                    }
                } else {
                    echo "<option value=''>Tidak ada wilayah</option>";
                }
                ?>
            </select>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Button Submit -->
        <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
    </form>

    <!-- Button Kembali -->
    <a href="admin-dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
