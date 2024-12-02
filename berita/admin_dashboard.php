<?php
session_start();
include '../config/config.php'; // Pastikan path ke config sudah benar

// Pastikan hanya admin yang dapat mengakses dashboard ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini.";
    header("Location: login.php"); // Ganti dengan halaman login atau halaman yang sesuai
    exit();
}

// Menampilkan pesan sukses atau error
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']); // Hapus pesan setelah ditampilkan
}

// Memproses data jika ada pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengamankan data input
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

    // Proses upload gambar jika ada
    $gambar = null;
    $target_file = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $target_file = 'uploads/' . basename($_FILES['gambar']['name']);

        // Validasi tipe dan ukuran file
        if (in_array($_FILES['gambar']['type'], $allowed_types) && $_FILES['gambar']['size'] < 2 * 1024 * 1024) {
            // Pastikan file tidak menimpa yang sudah ada
            if (!file_exists($target_file)) {
                move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
            } else {
                echo "<div class='alert alert-danger'>File sudah ada, coba ganti nama file.</div>";
                exit();
            }
        } else {
            echo "<div class='alert alert-danger'>File tidak valid atau ukuran terlalu besar.</div>";
            exit();
        }
    }

    // Menyimpan data berita ke database
    $sql = "INSERT INTO berita (judul, konten, kategori, gambar, tanggal_publikasi) VALUES ('$judul', '$konten', '$kategori', '$target_file', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Kirim email ke admin setelah berita berhasil ditambahkan
        $to = "deftricoca1@gmail.com";
        $subject = "Berita Baru Ditambahkan";
        $message = "Berita baru berjudul '$judul' telah ditambahkan oleh kontributor. Silakan cek di dashboard admin.";
        $headers = "From: no-reply@example.com";
        mail($to, $subject, $message, $headers);

        $_SESSION['message'] = "Berita berhasil ditambahkan!";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1100px;
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
        <h1>Admin Dashboard</h1>
        <div class="mb-3">
            <a href="tambah_berita.php" class="btn btn-primary">Tambah Berita</a>
            <a href="kelola_kategori.php" class="btn btn-secondary">Kelola Kategori</a>
            <a href="kelola_galeri.php" class="btn btn-info">Kelola Galeri</a> <!-- Tombol Baru -->
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <button onclick="toggleDarkMode()" class="btn btn-dark ml-2">Toggle Dark Mode</button> <!-- Tombol dark mode -->
        </div>

        <!-- Menampilkan pesan sukses atau error -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']); // Hapus pesan setelah ditampilkan
        }
        ?>

        <!-- Daftar Berita -->
        <h2 class="mt-4">Daftar Berita</h2>

        <?php
        $sql = "SELECT * FROM berita ORDER BY tanggal_publikasi DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card my-3'>";
                echo "<div class='card-body'>";
                echo "<h3 class='card-title'>" . htmlspecialchars($row['judul']) . "</h3>";
                
                if (!empty($row['gambar']) && file_exists('../' . $row['gambar'])) {
                    echo "<img src='../" . htmlspecialchars($row['gambar']) . "' alt='Gambar Berita' class='img-fluid mb-3' style='max-height: 300px; object-fit: cover;'>";
                }

                echo "<p class='card-text'>" . nl2br(htmlspecialchars(substr($row['konten'], 0, 200))) . "...</p>"; // Preview konten
                echo "<p class='text-muted'>Kategori: " . htmlspecialchars($row['kategori']) . "</p>";
                echo "<p class='text-muted'>Tanggal Publikasi: " . htmlspecialchars(date("d M Y H:i", strtotime($row['tanggal_publikasi']))) . "</p>";
                echo "<a href='edit_berita.php?id=" . urlencode($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a> ";
                echo "<a href='hapus_berita.php?id=" . urlencode($row['id']) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus berita ini?\")' class='btn btn-danger btn-sm'>Hapus</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-muted'>Tidak ada berita.</p>";
        }
        ?>

        <!-- Daftar Komentar -->
        <h2 class="mt-4">Daftar Komentar</h2>

        <?php
        // Mengambil semua komentar dari database
        $sql_komentar = "SELECT komentar.*, berita.judul FROM komentar JOIN berita ON komentar.berita_id = berita.id ORDER BY komentar.tanggal DESC";
        $result_komentar = $conn->query($sql_komentar);

        if ($result_komentar && $result_komentar->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead class='thead-dark'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Berita</th>";
            echo "<th>Nama</th>";
            echo "<th>Isi Komentar</th>";
            echo "<th>Tanggal</th>";
            echo "<th>Aksi</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while ($komentar = $result_komentar->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($komentar['id']) . "</td>";
                echo "<td>" . htmlspecialchars($komentar['judul']) . "</td>";
                echo "<td>" . htmlspecialchars($komentar['nama']) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($komentar['isi'])) . "</td>";
                echo "<td>" . htmlspecialchars($komentar['tanggal']) . "</td>";
                echo "<td>";
                echo "<a href='hapus_komentar.php?id=" . urlencode($komentar['id']) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus komentar ini?\")' class='btn btn-danger btn-sm'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='text-muted'>Tidak ada komentar.</p>";
        }
        ?>

        <!-- Daftar Pengguna -->
        <h2 class="mt-4">Daftar Pengguna</h2>
        <a href="tambah_pengguna.php" class="btn btn-success mb-3">Tambah Pengguna Baru</a>

        <?php
        $sql_pengguna = "SELECT * FROM users ORDER BY id DESC";
        $result_pengguna = $conn->query($sql_pengguna);

        if ($result_pengguna && $result_pengguna->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead class='thead-dark'><tr><th>ID</th><th>Nama</th><th>Email</th><th>Peran</th><th>Waktu Dibuat</th><th>Aksi</th></tr></thead><tbody>";
            while ($user = $result_pengguna->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                echo "<td>" . htmlspecialchars($user['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars(ucfirst($user['role'])) . "</td>";
                echo "<td>" . htmlspecialchars(date("d M Y H:i", strtotime($user['created_at']))) . "</td>";
                echo "<td>
                        <a href='edit_pengguna.php?id=" . urlencode($user['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='hapus_pengguna.php?id=" . urlencode($user['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna ini?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-muted'>Tidak ada pengguna.</p>";
        }
        ?>

        <!-- Statistik Kunjungan Halaman -->
        <h3 class="mt-5">Statistik Kunjungan Halaman</h3>
        <?php
        $sql_stat = "SELECT halaman, SUM(kunjungan) as total_kunjungan FROM statistik GROUP BY halaman ORDER BY total_kunjungan DESC";
        $result_stat = $conn->query($sql_stat);

        if ($result_stat && $result_stat->num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Halaman</th>";
            echo "<th>Total Kunjungan</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while ($row = $result_stat->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['halaman']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total_kunjungan']) . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='text-muted'>Tidak ada data statistik.</p>";
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
