<?php
// Koneksi ke database
include '../config/config.php';

// Jumlah berita per halaman
$berita_per_page = 6;

// Mengambil nomor halaman dari URL (default ke 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $berita_per_page;

// Mengambil kategori dari URL, jika ada
$kategori_terpilih = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query untuk mengambil data berita berdasarkan kategori (jika ada)
if ($kategori_terpilih) {
    $sql = "SELECT * FROM berita WHERE kategori = '" . $conn->real_escape_string($kategori_terpilih) . "' ORDER BY tanggal_publikasi DESC LIMIT $start, $berita_per_page";
} else {
    $sql = "SELECT * FROM berita ORDER BY tanggal_publikasi DESC LIMIT $start, $berita_per_page";
}

$result = $conn->query($sql);

// Query untuk menghitung total berita
if ($kategori_terpilih) {
    $total_sql = "SELECT COUNT(*) FROM berita WHERE kategori = '" . $conn->real_escape_string($kategori_terpilih) . "'";
} else {
    $total_sql = "SELECT COUNT(*) FROM berita";
}
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_berita = $total_row[0];
$total_page = ceil($total_berita / $berita_per_page);
?>

<?php include 'includes/header.php' ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumpulan Berita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: left;
            padding: 15px;
            height: 100%;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            height: 60px; /* Membatasi tinggi judul */
            overflow: hidden; /* Menyembunyikan teks yang melebihi batas */
            text-overflow: ellipsis;
        }
        .card-text {
            font-size: 0.95rem;
            height: 75px; /* Membatasi panjang konten */
            overflow: hidden;
            text-overflow: ellipsis; /* Menambahkan "..." pada teks yang terpotong */
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Agar tombol tetap di bawah */
        }
        .card-img-top {
            object-fit: cover;
            height: 200px; /* Menjaga gambar tetap proporsional */
            width: 100%;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination .page-item .page-link {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Kumpulan Berita</h1>

        <!-- Filter berdasarkan kategori -->
        <div class="text-center mb-4">
            <a href="?kategori=ADVIN" class="btn btn-secondary">ADVIN</a>
            <a href="?kategori=KB" class="btn btn-secondary">KB</a>
            <a href="?kategori=KS" class="btn btn-secondary">KS</a>
            <a href="kumpulan_berita.php" class="btn btn-secondary">Semua Berita</a>
        </div>

        <div class="row">
            <?php
            // Cek jika ada berita di database
            if ($result->num_rows > 0) {
                // Output data setiap baris
                while ($row = $result->fetch_assoc()) {
                    // Ambil data judul, konten dan tanggal publikasi
                    $judul = htmlspecialchars($row['judul']);
                    $konten = nl2br(htmlspecialchars(substr($row['konten'], 0, 150))); // Potong konten untuk preview
                    $tanggal = date("d-m-Y", strtotime($row['tanggal_publikasi']));
                    $id_berita = $row['id'];
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="./<?php echo htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="Gambar Berita">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $judul; ?></h5>
                            <p class="card-text"><?php echo $konten; ?>...</p>
                            <p class="text-muted">Tanggal: <?php echo $tanggal; ?></p>
                            <a href="detail_berita.php?id=<?php echo urlencode($id_berita); ?>" class="btn btn-primary mt-auto">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<div class='col-12 text-center'><p>Tidak ada berita yang tersedia.</p></div>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&kategori=<?php echo urlencode($kategori_terpilih); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_page): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
