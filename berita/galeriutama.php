<!-- Galeri Section -->
<section class="galeri-section mb-5" id="galeri">
    <div class="container">
        <h2 class="mb-4 text-center" style="font-family: 'Arial', sans-serif; color: #333;">Galeri Utama</h2>
        <div class="row">
            <?php
            // Modifikasi query untuk menampilkan hanya gambar dengan jenis 'event'
            $galeri_sql = "SELECT * FROM galeri WHERE jenis = 'event' ORDER BY id DESC";
            $galeri_result = $conn->query($galeri_sql);

            if ($galeri_result && $galeri_result->num_rows > 0) {
                // Loop untuk menampilkan gambar-gambar dari database
                while ($galeri_item = $galeri_result->fetch_assoc()) {
                    // Mendapatkan path gambar dan alt dari database
                    $galeri_image_path = htmlspecialchars($galeri_item['file_path']);
                    $galeri_image_alt = htmlspecialchars($galeri_item['caption']); // Jika caption ada
                    $galeri_id = $galeri_item['id']; // ID gambar
                    echo "<div class='col-md-4 mb-4'>"; 
                    echo "<div class='card h-100 shadow-sm border-light'>"; // Card dengan shadow untuk efek depth
                    echo "<a href='detail_gambar.php?id=" . $galeri_id . "' target='_blank'>";
                    // Menampilkan gambar jika ada
                    echo "<img src='../" . $galeri_image_path . "' alt='" . $galeri_image_alt . "' class='card-img-top' style='object-fit: cover; height: 200px;'>";
                    echo "</a>";
                    echo "<div class='card-body d-flex flex-column'>";
                    echo "<h5 class='card-title' style='font-size: 1.1rem; color: #333;'>" . $galeri_image_alt . "</h5>"; // Menampilkan caption sebagai judul gambar
                    echo "<a href='detail_gambar.php?id=" . $galeri_id . "' class='btn btn-primary mt-auto' style='width: 100%;'>Lihat Detail</a>";
                    echo "</div>"; // Tutup card-body
                    echo "</div>"; // Tutup card
                    echo "</div>"; // Tutup col-md-4
                }
            } else {
                // Jika tidak ada gambar jenis 'event'
                echo "<div class='col-12'><p class='text-center text-muted'>Tidak ada gambar dengan jenis 'event' di galeri.</p></div>";
            }
            ?>
        </div>
        <!-- Tombol untuk mengakses galeri lebih banyak -->
        <div class="text-center mt-4">
            <a href="galeri.php" class="btn btn-secondary btn-lg" style="padding: 10px 30px; font-size: 1rem;">Lihat Galeri Selengkapnya</a>
        </div>
    </div>
</section>

<!-- Tambahkan styling CSS -->
<style>
    .card-body {
        padding: 15px;
    }
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    .card-img-top {
        border-bottom: 1px solid #ddd;
    }
</style>
