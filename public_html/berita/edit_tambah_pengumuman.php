<?php
// Koneksi ke database
include '../config/config.php'; // Pastikan path ke config sudah benar
include 'includes/renja-header.php';
// Menentukan ID pengumuman yang ingin diedit (ID 99)
$id = 99;

// Mengambil data pengumuman dengan ID 99
$query = "SELECT * FROM pengumuman WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Mengambil data pengumuman yang ditemukan
    $pengumuman = mysqli_fetch_assoc($result);
    $judul = $pengumuman['judul'];
    $isi = $pengumuman['isi'];
    $tanggal = $pengumuman['tanggal']; // Tanggal yang ada di database
} else {
    // Jika pengumuman tidak ditemukan
    echo "<div class='alert alert-danger'>Pengumuman tidak ditemukan!</div>";
    exit;
}

// Mengecek apakah form telah disubmit untuk memperbarui pengumuman
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menangkap data dari form
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = $_POST['tanggal']; // Mengambil tanggal yang dipilih dari form

    // Query untuk memperbarui pengumuman dengan ID 99
    $updateQuery = "UPDATE pengumuman SET judul = '$judul', isi = '$isi', tanggal = '$tanggal' WHERE id = '$id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Menampilkan pesan sukses jika pengumuman berhasil diperbarui
        echo "<div class='alert alert-success'>Pengumuman berhasil diperbarui!</div>";
    } else {
        // Menampilkan pesan error jika terjadi masalah
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

// Menutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengumuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2>Edit Pengumuman</h2>
        <form action="edit_tambah_pengumuman.php" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Pengumuman</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul; ?>" required>
            </div>
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Pengumuman</label>
                <textarea class="form-control" id="isi" name="isi" rows="4" required><?php echo $isi; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Pengumuman</label>
                <!-- Menggunakan input date, yang akan mengonversi tanggal ke format yang bisa dipilih -->
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d', strtotime($tanggal)); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Pengumuman</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
