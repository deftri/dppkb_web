<?php
session_start();
include '../config/config.php'; // Pastikan koneksi database sudah benar

// Pastikan pengguna sudah login (user_id ada di session)
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk mendapatkan notifikasi yang belum dibaca
$sql_notifikasi = "SELECT * FROM notifikasi WHERE user_id='$user_id' AND status='belum_dibaca' ORDER BY tanggal DESC";
$result_notifikasi = $conn->query($sql_notifikasi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna</title>
    <meta name="description" content="Halaman Dashboard Pengguna">
    <meta name="keywords" content="notifikasi, dashboard, pengguna">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Selamat Datang, Pengguna!</h1>

        <!-- Menampilkan Notifikasi -->
        <h3>Notifikasi</h3>
        <?php
        if ($result_notifikasi->num_rows > 0) {
            while ($notif = $result_notifikasi->fetch_assoc()) {
                echo "<div class='alert alert-info'>";
                echo htmlspecialchars($notif['isi_notifikasi']);
                echo "</div>";
            }
        } else {
            echo "<p class='text-muted'>Tidak ada notifikasi baru.</p>";
        }
        ?>

        <!-- Tautan untuk Logout -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>
