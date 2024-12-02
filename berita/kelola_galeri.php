<?php
session_start();
include '../config/config.php'; // Pastikan path ke config sudah benar

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
    header("Location: admin_dashboard.php"); // Redirect ke dashboard admin
    exit();
}

// Menangani penghapusan gambar
if (isset($_GET['hapus'])) {
    $id_galeri = (int)$_GET['hapus'];

    // Mengambil data gambar sebelum dihapus untuk menghapus file
    $stmt = $conn->prepare("SELECT file_path FROM galeri WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_galeri);
        $stmt->execute();
        $stmt->bind_result($file_path);
        if ($stmt->fetch()) {
            // Hapus file dari server
            if (file_exists('../' . $file_path)) {
                unlink('../' . $file_path);
            }

            // Hapus data dari database
            $stmt->close();
            $del_stmt = $conn->prepare("DELETE FROM galeri WHERE id = ?");
            if ($del_stmt) {
                $del_stmt->bind_param("i", $id_galeri);
                if ($del_stmt->execute()) {
                    $_SESSION['message'] = "Gambar berhasil dihapus dari galeri.";
                } else {
                    $_SESSION['error'] = "Gagal menghapus gambar: " . htmlspecialchars($del_stmt->error);
                }
                $del_stmt->close();
            } else {
                $_SESSION['error'] = "Gagal menyiapkan statement penghapusan: " . htmlspecialchars($conn->error);
            }
        } else {
            $_SESSION['error'] = "Gambar tidak ditemukan.";
        }
    } else {
        $_SESSION['error'] = "Gagal menyiapkan statement: " . htmlspecialchars($conn->error);
    }

    header("Location: kelola_galeri.php");
    exit();
}

// Mengambil semua data galeri
$galeri_sql = "SELECT * FROM galeri ORDER BY upload_date DESC";
$galeri_result = $conn->query($galeri_sql);
if (!$galeri_result) {
    error_log("Query gagal: (" . $conn->errno . ") " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Galeri</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin-top: 40px;
        }
        .galeri-images img {
            max-height: 150px;
            object-fit: cover;
            transition: transform 0.2s;
        }
        .galeri-images img:hover {
            transform: scale(1.05);
        }
        .caption {
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola Galeri</h1>
        <a href="tambah_galeri.php" class="btn btn-primary mb-3">Tambah Gambar Baru</a>
        <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
        
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

        <h2 class="mt-4">Daftar Gambar Galeri</h2>

        <?php
        if ($galeri_result && $galeri_result->num_rows > 0) {
            echo "<div class='row galeri-images'>";
            while ($galeri = $galeri_result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card'>";
                // Tampilkan gambar
                if (!empty($galeri['file_path']) && file_exists('../' . $galeri['file_path'])) {
                    echo "<img src='../" . htmlspecialchars($galeri['file_path']) . "' class='card-img-top' alt='Gambar Galeri'>";
                } else {
                    echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image'>";
                }
                echo "<div class='card-body'>";
                echo "<p class='card-text'>" . htmlspecialchars($galeri['caption']) . "</p>";
                echo "<p class='card-text'><small class='text-muted'>Jenis: " . htmlspecialchars($galeri['jenis']) . "</small></p>";
                echo "<a href='kelola_galeri.php?hapus=" . urlencode($galeri['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus gambar ini?\")'>Hapus</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-muted'>Tidak ada gambar di galeri.</p>";
        }
        ?>

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
<?php
// Menutup koneksi database
$conn->close();
?>
