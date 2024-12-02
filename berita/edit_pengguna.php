<?php
// edit_pengguna.php
session_start();
include '../config/config.php';
include 'log_aktivitas.php';

// Cek apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Cek parameter ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID pengguna tidak valid.";
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = (int)$_GET['id'];

// Ambil data pengguna
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Pengguna tidak ditemukan.";
    header("Location: admin_dashboard.php");
    exit();
}
$user = $result->fetch_assoc();

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email tidak valid.";
        header("Location: edit_pengguna.php?id=$user_id");
        exit();
    }

    // Siapkan query
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql_update = "UPDATE users SET nama='$nama', email='$email', role='$role', password='$hashed_password' WHERE id=$user_id";
    } else {
        $sql_update = "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$user_id";
    }

    if ($conn->query($sql_update) === TRUE) {
        // Log aktivitas
        logActivity($conn, $_SESSION['user_id'], "Mengedit pengguna: $nama ($email)");

        $_SESSION['message'] = "Pengguna berhasil diperbarui!";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        if ($conn->errno == 1062) { // Duplicate entry
            $_SESSION['error'] = "Email sudah terdaftar.";
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
        header("Location: edit_pengguna.php?id=$user_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Pengguna</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>
        <form method="POST" action="edit_pengguna.php?id=<?php echo $user_id; ?>">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Peran:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>Editor</option>
                    <option value="contributor" <?php if ($user['role'] == 'contributor') echo 'selected'; ?>>Contributor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password (Biarkan kosong jika tidak diubah):</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
