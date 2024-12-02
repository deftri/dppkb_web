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

// Mengecek apakah ID galeri diberikan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID galeri tidak valid.";
    header("Location: kelola_galeri.php");
    exit();
}

$id_galeri = (int)$_GET['id'];

// Mengambil data galeri berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM galeri WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_galeri);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Gambar galeri tidak ditemukan.";
        header("Location: kelola_galeri.php");
        exit();
    }
    $galeri = $result->fetch_assoc();
    $stmt->close();
} else {
    $_SESSION['error'] = "Gagal menyiapkan statement: " . htmlspecialchars($conn->error);
    header("Location: kelola_galeri.php");
    exit();
}

// Memproses data jika ada pengiriman form
// Memproses data jika ada pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengamankan dan mengambil data input
    $caption = mysqli_real_escape_string($conn, trim($_POST['caption']));
    $jenis = mysqli_real_escape_string($conn, trim($_POST['jenis']));

    // Validasi input: hanya caption yang wajib diisi, jenis boleh kosong
    if (empty($caption)) {
        $error = "Caption harus diisi.";
    } else {
        // Jika jenis kosong, set menjadi NULL untuk database
        if (empty($jenis)) {
            $jenis = NULL;
        }

        // Memperbarui data di database
        $update_stmt = $conn->prepare("UPDATE galeri SET caption = ?, jenis = ? WHERE id = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("ssi", $caption, $jenis, $id_galeri);
            if ($update_stmt->execute()) {
                $_SESSION['message'] = "Gambar galeri berhasil diperbarui.";
                header("Location: kelola_galeri.php");
                exit();
            } else {
                $error = "Gagal memperbarui gambar galeri: " . htmlspecialchars($update_stmt->error);
            }
            $update_stmt->close();
        } else {
            $error = "Gagal menyiapkan statement: " . htmlspecialchars($conn->error);
        }
    }

    // Menyimpan pesan error jika ada
    if (!empty($error)) {
        $_SESSION['error'] = $error;
        header("Location: edit_galeri.php?id=" . urlencode($id_galeri));
        exit();
    }
}
    
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Galeri</title>
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
        <h1>Edit Gambar Galeri</h1>
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

        <form action="edit_galeri.php?id=<?php echo urlencode($id_galeri); ?>" method="POST">
            <div class="form-group">
                <label for="gambar">Gambar:</label><br>
                <?php
                if (!empty($galeri['file_path']) && file_exists('../' . $galeri['file_path'])) {
                    echo "<img src='../" . htmlspecialchars($galeri['file_path']) . "' alt='Gambar Galeri' class='img-thumbnail mb-3' style='max-width: 100%; height: auto;'>";
                } else {
                    echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' alt='No Image' class='img-thumbnail mb-3'>";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="caption">Caption:</label>
                <input type="text" name="caption" id="caption" class="form-control" required value="<?php echo htmlspecialchars($galeri['caption']); ?>">
            </div>
            <div class="form-group">
                <label for="jenis">Jenis:</label>
                <select name="jenis" id="jenis" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="cover" <?php echo ($galeri['jenis'] === 'cover') ? 'selected' : ''; ?>>Cover</option>
                    <option value="logo" <?php echo ($galeri['jenis'] === 'logo') ? 'selected' : ''; ?>>Logo</option>
                    <option value="event" <?php echo ($galeri['jenis'] === 'event') ? 'selected' : ''; ?>>Event</option>
                    <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                </select>
            </div>
            <button type="submit" class="btn btn-success">Perbarui Gambar</button>
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
<?php
// Menutup koneksi database
$conn->close();
?>
