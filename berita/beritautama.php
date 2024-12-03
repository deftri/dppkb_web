<div class="container-fluid mb-5">
    <div class="row">
        <!-- BERITA DPPKB di Tengah -->
        <div class="col-md-9">
            <h2 class="text-center mb-4" style="font-family: 'Arial', sans-serif; color: #333;">BERITA DPPKB</h2>
            <div class="row">
                <?php
                // Membatasi jumlah berita yang ditampilkan
                $berita_limit = 4;
                $berita_counter = 0;
                
                // Memeriksa apakah ada hasil query berita
                if ($result && $result->num_rows > 0) {
                    // Loop untuk menampilkan setiap berita
                    while ($row = $result->fetch_assoc()) {
                        if ($berita_counter >= $berita_limit) break; // Batas 4 berita
                        
                        echo "<div class='col-md-3 mb-4'>";
                        echo "<div class='card h-100 shadow-sm border-light'>";
                        
                        // Cek apakah gambar ada dan valid
                        if (!empty($row['gambar']) && file_exists(__DIR__ . '/' . $row['gambar'])) {
                            echo "<img src='" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar Berita' style='object-fit: cover; height: 200px;'>";
                        } else {
                            echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image' style='object-fit: cover; height: 200px;'>";
                        }
    
                        // Isi berita
                        echo "<div class='card-body d-flex flex-column'>";
                        echo "<h5 class='card-title' style='font-size: 1.2rem; font-weight: bold; color: #333;'>" . htmlspecialchars($row['judul']) . "</h5>";
                        echo "<p class='card-text' style='font-size: 1rem; color: #555;'>" . nl2br(htmlspecialchars(substr($row['konten'], 0, 100))) . "...</p>"; // Potongan konten
                        echo "<p class='text-muted mt-auto' style='font-size: 0.9rem;'>Tanggal Publikasi: " . htmlspecialchars(date("d M Y", strtotime($row['tanggal_publikasi']))) . "</p>";
                        echo "<a href='detail_berita.php?id=" . urlencode($row['id']) . "' class='btn btn-primary mt-2' style='width: 100%;'>Baca Selengkapnya</a>";
                        echo "</div></div></div>";
                        
                        $berita_counter++;
                    }
                } else {
                    // Jika tidak ada berita
                    echo "<div class='col-12'><p class='text-center text-muted'>Tidak ada berita yang sesuai dengan kata kunci.</p></div>";
                }
                ?>
            </div>
            <div class="text-center">
                <a href="kumpulan_berita.php" class="btn btn-secondary btn-lg" style="padding: 10px 30px; font-size: 1rem;">Berita Selengkapnya</a>
            </div>
        </div>

        <!-- Kategori di Kanan -->
        <div class="col-md-3" style="margin-top: 30px;">
            <div class="card shadow-sm">
                <div class="card-header" style="font-weight: bold; background-color: #f8f9fa; text-align: center;">
                    <h5>Kategori</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        // Query untuk menarik kategori unik dari tabel berita
                        $kategori_query = "SELECT DISTINCT kategori FROM berita ORDER BY kategori";
                        $kategori_result = $conn->query($kategori_query);

                        if ($kategori_result && $kategori_result->num_rows > 0) {
                            // Loop untuk menampilkan kategori
                            while ($kategori = $kategori_result->fetch_assoc()) {
                                // Membuat link kategori yang mengarah ke halaman kumpulan berita sesuai kategori
                                echo "<li class='list-group-item'><a href='kumpulan_berita.php?kategori=" . urlencode($kategori['kategori']) . "' style='text-decoration: none; color: #007bff;'>" . htmlspecialchars($kategori['kategori']) . "</a></li>";
                            }
                        } else {
                            // Jika tidak ada kategori
                            echo "<li class='list-group-item text-muted'>Tidak ada kategori yang tersedia.</li>";
                        }
                        
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan styling CSS -->
<style>
    /* Menambahkan styling agar tampilan berita lebih rapat */
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
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    .col-md-12, .col-md-3 {
        padding-left: 0;
        padding-right: 0;
    }
    .card-body {
        padding: 15px;
    }
</style>
