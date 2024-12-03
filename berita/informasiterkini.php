<section class="informasi-terkini section mb-5">
    <div class="container">
        <h2 class="mb-4 text-center">Informasi Terkini</h2>
        <div class="row">
            <!-- Pengumuman Terbaru di Kiri -->
            <div class="col-md-6">
                <h4>Pengumuman</h4>
                <?php
                if ($pengumuman_result && $pengumuman_result->num_rows > 0) {
                    echo "<ul class='list-unstyled'>";
                    while ($pengumuman = $pengumuman_result->fetch_assoc()) {
                        echo "<li><i class='fas fa-bullhorn text-primary mr-2'></i>" . htmlspecialchars($pengumuman['judul']) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p class='text-muted'>Tidak ada pengumuman terbaru.</p>";
                }
                ?>
            </div>
            <!-- Berita Lama yang Hangat di Kanan -->
            <div class="col-md-6">
                <h4>Berita Lama yang Hangat</h4>
                <?php
                if ($berita_lama_result && $berita_lama_result->num_rows > 0) {
                    echo "<ul class='list-unstyled'>";
                    while ($berita_lama = $berita_lama_result->fetch_assoc()) {
                        echo "<li><i class='fas fa-fire text-danger mr-2'></i>" . htmlspecialchars($berita_lama['judul']) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p class='text-muted'>Tidak ada berita lama yang hangat.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</section>