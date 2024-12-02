<?php
include '../config/config.php'; // Ganti path sesuai lokasi file config Anda

$halaman = basename($_SERVER['PHP_SELF']);
$tanggal = date("Y-m-d");

// Mencatat atau memperbarui statistik kunjungan
$sql_statistik = "INSERT INTO statistik (halaman, kunjungan, tanggal) 
                  VALUES ('$halaman', 1, '$tanggal') 
                  ON DUPLICATE KEY UPDATE kunjungan = kunjungan + 1";
$conn->query($sql_statistik);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];
    $sql = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Kategori berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2; /* Latar belakang abu-abu terang */
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 800px;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
            color: #4e5d6c; /* Warna biru pastel yang lembut */
            font-weight: 600;
        }
        .form-label {
            font-weight: 600;
            color: #4e5d6c;
        }
        .btn-primary {
            background-color: #4e5d6c;
            border-color: #4e5d6c;
        }
        .btn-primary:hover {
            background-color: #3b4753;
            border-color: #2f3a44;
        }
        .category-list {
            margin-top: 20px;
        }
        .category-item {
            background: #FFFFFF;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        footer {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Kelola Kategori</h1>
    
    <!-- Form Tambah Kategori -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Tambah Kategori</button>
    </form>

    <!-- Daftar Kategori -->
    <h2 class="mt-5">Daftar Kategori</h2>
    <div class="category-list">
        <?php
        $sql = "SELECT * FROM kategori";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='category-item d-flex justify-content-between align-items-center'>";
                echo "<span>" . htmlspecialchars($row['nama_kategori']) . "</span>";
                echo " <a href='hapus_kategori.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus kategori ini?\")' class='btn btn-danger btn-sm'>Hapus</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='alert alert-info'>Tidak ada kategori.</div>";
        }
        ?>
    </div>

    <!-- Tombol Kembali ke Dashboard -->
    <div class="mt-4 text-center">
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard Admin</a>
    </div>

    <!-- Footer -->
    <footer>
        <a href="privacy_policy.php">Privacy Policy</a> | 
        <a href="terms_of_service.php">Terms of Service</a>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
