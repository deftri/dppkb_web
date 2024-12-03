<?php
session_start();
include '../config/config.php'; // Pastikan path ke config sudah benar

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
    header("Location: admin_dashboard.php"); // Redirect ke dashboard admin
    exit();
}

// Inisialisasi pesan error dan sukses
$message = '';
$error = '';

// Memproses data jika ada pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengamankan dan mengambil data input
    $caption = mysqli_real_escape_string($conn, trim($_POST['caption']));
    $jenis = mysqli_real_escape_string($conn, trim($_POST['jenis']));

    // Validasi input
    if (empty($caption) || empty($jenis)) {
        $error = "Semua field harus diisi.";
    } elseif (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] != 0) {
        $error = "Gambar harus diunggah.";
    } else {
        // Proses upload gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 10 * 1024 * 1024; // 2MB

        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = basename($_FILES['gambar']['name']);
        $file_size = $_FILES['gambar']['size'];
        $file_type = $_FILES['gambar']['type'];

        // Validasi tipe dan ukuran file
        if (!in_array($file_type, $allowed_types)) {
            $error = "Tipe file tidak diperbolehkan. Hanya JPG, PNG, dan GIF yang diperbolehkan.";
        } elseif ($file_size > $max_size) {
            $error = "Ukuran file terlalu besar. Maksimal 10 MB.";
        } else {
            // Tentukan direktori upload
            $upload_dir = '../uploads/galeri/'; // Pastikan direktori ini ada dan writable

            // Membuat direktori jika belum ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Menghindari penamaan file yang sama
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = uniqid('galeri_', true) . '.' . $file_ext;
            $target_file = $upload_dir . $new_file_name;

            // Memindahkan file ke direktori tujuan
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Menyimpan path relatif ke database
                $file_path = 'uploads/galeri/' . $new_file_name;

                // Menyimpan data ke database
                $stmt = $conn->prepare("INSERT INTO galeri (file_path, caption, upload_date, jenis) VALUES (?, ?, NOW(), ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $file_path, $caption, $jenis);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Gambar berhasil diunggah ke galeri.";
                        header("Location: kelola_galeri.php");
                        exit();
                    } else {
                        $error = "Gagal menyimpan data ke database: " . htmlspecialchars($stmt->error);
                        // Hapus file yang sudah diupload jika gagal menyimpan ke database
                        unlink($target_file);
                    }
                    $stmt->close();
                } else {
                    $error = "Gagal menyiapkan statement: " . htmlspecialchars($conn->error);
                    // Hapus file yang sudah diupload jika gagal menyiapkan statement
                    unlink($target_file);
                }
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        }
    }

    // Menyimpan pesan error jika ada
    if (!empty($error)) {
        $_SESSION['error'] = $error;
        header("Location: tambah_galeri.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Galeri</title>
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
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode .card {
            background-color: #1e1e1e;
            border-color: #333;
        }
        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Gambar ke Galeri</h1>
        <a href="kelola_galeri.php" class="btn btn-secondary mb-3">Kembali ke Kelola Galeri</a>
        
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

        <form action="tambah_galeri.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="gambar">Gambar:</label>
                <input type="file" name="gambar" id="gambar" class="form-control-file" required accept="image/*">
            </div>
            <div class="form-group">
                <label for="caption">Caption:</label>
                <input type="text" name="caption" id="caption" class="form-control" required value="<?php echo isset($caption) ? htmlspecialchars($caption) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="jenis">Jenis:</label>
                <select name="jenis" id="jenis" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="cover">SAMPUL</option>
                    <option value="logo">LOGO</option>
                    <option value="event">KEGIATAN</option>
                    <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                </select>
            </div>
            <button type="submit" class="btn btn-success">Tambah Gambar</button>
            <button type="button" onclick="toggleDarkMode()" class="btn btn-dark ml-2">Toggle Dark Mode</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Mengaktifkan dark mode berdasarkan localStorage
        document.addEventListener("DOMContentLoaded", () => {
            if (localStorage.getItem("dark-mode") === "enabled") {
                document.body.classList.add("dark-mode");
            }
        });

        // Fungsi toggle untuk dark mode
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem("dark-mode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
        }
    </script>
</body>
</html>
