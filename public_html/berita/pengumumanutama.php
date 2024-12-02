<!-- Pengumuman Section -->
<section class="announcement-section mb-5">
    <div class="container-fluid"> <!-- Ganti container menjadi container-fluid untuk lebar penuh -->
        <h2 class="text-center mb-4">Pengumuman Terbaru</h2>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12"> <!-- Kolom menggunakan lebar penuh -->
                <?php if ($pengumuman_result && $pengumuman_result->num_rows > 0): ?>
                    <?php while ($pengumuman = $pengumuman_result->fetch_assoc()): ?>
                        <div class="announcement-card mb-4">
                            <h4 class="announcement-title"><?php echo htmlspecialchars($pengumuman['judul']); ?></h4>
                            <p class="announcement-date"><?php echo date("d-m-Y", strtotime($pengumuman['tanggal'])); ?></p>
                            <p class="announcement-description"><?php echo nl2br(htmlspecialchars($pengumuman['isi'])); ?></p>
                            <a href="#" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-muted">Tidak ada pengumuman terbaru.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CSS untuk tampilan pengumuman -->
<style>
   /* Pengumuman Section */
.announcement-section {
    padding: 80px 0;
    background-color: #f7f7f7;
}

/* Kartu pengumuman */
.announcement-card {
    background-color: #fff;
    padding: 60px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    text-align: left; /* Mengubah alignment ke kiri agar lebih nyaman dibaca */
    margin: 20px 0; /* Menambah margin vertikal untuk pemisahan antar kartu */
    text-align: center;
}

/* Judul Pengumuman */
.announcement-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-align: center;
}

/* Tanggal Pengumuman */
.announcement-date {
    font-size: 2rem;
    color: #888;
    margin-bottom: 10px;
    text-align: center;
}

/* Deskripsi Pengumuman */
.announcement-description {
    font-size: 4rem;
    font-weight: bold;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
    text-align: center;
}

/* Tombol Baca Selengkapnya */
.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 4px 8px;
    text-transform: uppercase;
    font-size: 0.875rem;
    border-radius: 20px;
    transition: background-color 0.3s ease;
    text-align: center;
}

.btn-primary:hover {
    background-color: #0056b3;
    text-align: center;
}

/* Gaya untuk teks yang tidak aktif */
.text-muted {
    color: #aaa;
}

/* Gaya untuk teks di tengah */
.text-center {
    text-align: center;
}

</style>
