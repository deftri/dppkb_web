<?php
$halaman = 'berita.php';
include '../includes/header.php';
?>

<!-- Konten Berita -->
<div class="container my-5">
    <div class="row">
        <!-- Daftar Berita -->
        <div class="col-md-8">
            <h2 class="mb-4">Berita Terbaru</h2>
            <?php
            // Contoh query untuk mengambil berita dari database
            // Pastikan Anda sudah melakukan koneksi database sebelumnya
            if (isset($_GET['cari'])) {
                $cari = $_GET['cari'];
                $query = "SELECT * FROM berita WHERE judul LIKE '%$cari%' OR isi LIKE '%$cari%'";
            } else {
                $query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 10";
            }
            $result = $conn->query($query);

            if ($result->num_rows > 0):
                while($berita = $result->fetch_assoc()):
            ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($berita['judul']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo date('d M Y', strtotime($berita['tanggal'])); ?></h6>
                        <p class="card-text"><?php echo substr(htmlspecialchars($berita['isi']), 0, 200); ?>...</p>
                        <a href="detail.php?id=<?php echo $berita['id']; ?>" class="card-link">Baca Selengkapnya</a>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <p>Tidak ada berita ditemukan.</p>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar (Optional) -->
        <div class="col-md-4">
            <h2 class="mb-4">Kategori</h2>
            <ul class="list-group">
                <li class="list-group-item"><a href="#">Kategori 1</a></li>
                <li class="list-group-item"><a href="#">Kategori 2</a></li>
                <li class="list-group-item"><a href="#">Kategori 3</a></li>
                <li class="list-group-item"><a href="#">Kategori 4</a></li>
            </ul>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
