<?php
// Aktifkan error reporting untuk debugging
include("../config/config.php"); // Pastikan path ke config sudah benar

session_start();  // Memastikan sesi dimulai

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
    header("Location: login.php");  // Redirect ke login jika bukan admin
    exit();
}

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
        $judul = mysqli_real_escape_string($conn, $_POST['judul']);
        $konten = mysqli_real_escape_string($conn, $_POST['konten']);
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
        $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);

        // Validasi input
        if (empty($judul) || empty($konten) || empty($kategori) || empty($jenis)) {
            $error = "Silakan isi semua field.";
        } else {
            // Proses upload gambar jika ada
            $gambar = null;
            $target_file = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $target_file = 'uploads/' . basename($_FILES['gambar']['name']);

                // Validasi tipe dan ukuran file
                if (in_array($_FILES['gambar']['type'], $allowed_types) && $_FILES['gambar']['size'] < 2 * 1024 * 1024) {
                    // Memindahkan file gambar ke folder upload
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                        $gambar = $target_file;
                    } else {
                        $error = "Gagal mengunggah gambar.";
                    }
                } else {
                    $error = "Format gambar tidak valid atau ukuran terlalu besar.";
                }
            }

            // Menyimpan berita ke database
            if (empty($error)) {
                $tanggal_publikasi = date("Y-m-d H:i:s");  // Menentukan tanggal publikasi
                $view_count = 0;  // Set default view_count
                $kunjungan = 0;   // Set default kunjungan

                // Menyiapkan query untuk memasukkan data berita ke database
                $sql = "INSERT INTO berita (judul, konten, tanggal_publikasi, gambar, kategori, view_count, kunjungan, jenis)
                        VALUES ('$judul', '$konten', '$tanggal_publikasi', '$gambar', '$kategori', $view_count, $kunjungan, '$jenis')";

                if ($conn->query($sql) === TRUE) {
                    $message = "Berita berhasil ditambahkan!";
                } else {
                    $error = "Error: " . $conn->error;
                }
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
    <title>Tambah Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Berita</h2>

        <!-- Tampilkan pesan sukses atau error -->
        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="tambah_berita.php" method="POST" enctype="multipart/form-data">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <!-- Judul Berita -->
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Berita</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>

            <!-- Konten Berita -->
            <div class="mb-3">
                <label for="konten" class="form-label">Konten Berita</label>
                <textarea class="form-control" id="konten" name="konten" rows="4" required></textarea>
            </div>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="SEKRETARIAT">SEKRETARIAT</option>
                    <option value="KB">KB</option>
                    <option value="ADVIN">ADVIN</option>
                    <option value="KS">KS</option>
                </select>
            </div>

            <!-- Jenis -->
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <input type="text" class="form-control" id="jenis" name="jenis" required>
            </div>

            <!-- Gambar -->
            <div class="mb-3">
                <label for="gambar" class="form-label">Upload Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Tambahkan Berita</button>
        </form>
    </div>
</body>
</html>
