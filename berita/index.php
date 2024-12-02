<?php
// index.php
include '../config/config.php'; // Ganti path sesuai lokasi file config Anda

session_start();

// Mengatur halaman saat ini
$halaman = basename($_SERVER['PHP_SELF']);
$tanggal = date("Y-m-d");

// Mencatat atau memperbarui statistik kunjungan
$check_statistik = $conn->prepare("SELECT kunjungan FROM statistik WHERE halaman = ? AND tanggal = ?");
if ($check_statistik) {
    $check_statistik->bind_param("ss", $halaman, $tanggal);
    $check_statistik->execute();
    $check_statistik->store_result();

    if ($check_statistik->num_rows > 0) {
        // Jika data sudah ada, update kunjungan
        $update_statistik = $conn->prepare("UPDATE statistik SET kunjungan = kunjungan + 1 WHERE halaman = ? AND tanggal = ?");
        if ($update_statistik) {
            $update_statistik->bind_param("ss", $halaman, $tanggal);
            $update_statistik->execute();
            $update_statistik->close();
        } else {
            // Handle error jika prepare gagal
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
    } else {
        // Jika data belum ada, insert data baru
        $insert_statistik = $conn->prepare("INSERT INTO statistik (halaman, kunjungan, tanggal) VALUES (?, 1, ?)");
        if ($insert_statistik) {
            $insert_statistik->bind_param("ss", $halaman, $tanggal);
            $insert_statistik->execute();
            $insert_statistik->close();
        } else {
            // Handle error jika prepare gagal
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
    }
    $check_statistik->close();
} else {
    // Handle error jika prepare gagal
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Setup Pencarian dan Kategori
$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Pagination setup
$limit = 6; // Jumlah berita per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter berita berdasarkan kategori atau kata kunci
if ($keyword) {
    $sql = "SELECT * FROM berita WHERE judul LIKE CONCAT('%', ?, '%') OR konten LIKE CONCAT('%', ?, '%') ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssii", $keyword, $keyword, $limit, $offset);
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
} elseif ($kategori_filter) {
    $sql = "SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sii", $kategori_filter, $limit, $offset);
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
} else {
    $sql = "SELECT * FROM berita ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}

if (isset($stmt)) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
}

// Hitung total berita untuk pagination
if ($keyword) {
    $count_sql = "SELECT COUNT(*) AS total FROM berita WHERE judul LIKE CONCAT('%', ?, '%') OR konten LIKE CONCAT('%', ?, '%')";
    $count_stmt = $conn->prepare($count_sql);
    if ($count_stmt) {
        $count_stmt->bind_param("ss", $keyword, $keyword);
    }
} elseif ($kategori_filter) {
    $count_sql = "SELECT COUNT(*) AS total FROM berita WHERE kategori = ?";
    $count_stmt = $conn->prepare($count_sql);
    if ($count_stmt) {
        $count_stmt->bind_param("s", $kategori_filter);
    }
} else {
    $count_sql = "SELECT COUNT(*) AS total FROM berita";
    $count_stmt = $conn->prepare($count_sql);
}

if (isset($count_stmt)) {
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_rows = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);
    $count_stmt->close();
} else {
    $total_rows = 0;
    $total_pages = 1;
}

// Mengambil data Galeri
$galeri_sql = "SELECT * FROM galeri ORDER BY id DESC LIMIT 9"; // Atur jumlah galeri yang ditampilkan
$galeri_result = $conn->query($galeri_sql);
if (!$galeri_result) {
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil data Cover Image
$cover_sql = "SELECT * FROM galeri WHERE jenis = 'cover' ORDER BY id DESC LIMIT 1";
$cover_result = $conn->query($cover_sql);
if (!$cover_result) {
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil data statistik
$statistik_sql = "SELECT SUM(kunjungan) AS total_hit, 
                         SUM(CASE WHEN tanggal = CURDATE() THEN kunjungan ELSE 0 END) AS hari_ini,
                         SUM(CASE WHEN tanggal = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN kunjungan ELSE 0 END) AS kemarin 
                  FROM statistik";
$statistik_result = $conn->query($statistik_sql);
if ($statistik_result) {
    $statistik = $statistik_result->fetch_assoc();
} else {
    $statistik = ['total_hit' => 0, 'hari_ini' => 0, 'kemarin' => 0];
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil pengunjung terbanyak
$top_pengunjung_sql = "SELECT halaman, kunjungan FROM statistik ORDER BY kunjungan DESC LIMIT 1";
$top_pengunjung_result = $conn->query($top_pengunjung_sql);
if ($top_pengunjung_result && $top_pengunjung_result->num_rows > 0) {
    $top_pengunjung = $top_pengunjung_result->fetch_assoc();
} else {
    $top_pengunjung = ['halaman' => 'Tidak Ada', 'kunjungan' => 0];
    error_log("Query failed or no results: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil data jajak pendapat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['poll'])) {
    $vote = isset($_POST['vote']) ? $_POST['vote'] : '';
    // Validasi input vote
    $valid_votes = ['Ya', 'Cukup', 'Tidak', 'Tidak Tahu'];
    if (in_array($vote, $valid_votes)) {
        $poll_stmt = $conn->prepare("INSERT INTO poll_votes (vote) VALUES (?)");
        if ($poll_stmt) {
            $poll_stmt->bind_param("s", $vote);
            $poll_stmt->execute();
            $poll_stmt->close();
            // Redirect to avoid form resubmission
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
    }
}

// Mengambil hasil jajak pendapat
$poll_result = $conn->query("SELECT vote, COUNT(*) as count FROM poll_votes GROUP BY vote");
$poll_votes = ['Ya' => 0, 'Cukup' => 0, 'Tidak' => 0, 'Tidak Tahu' => 0];
if ($poll_result) {
    while ($row = $poll_result->fetch_assoc()) {
        if (array_key_exists($row['vote'], $poll_votes)) {
            $poll_votes[$row['vote']] = $row['count'];
        }
    }
}
$total_votes = array_sum($poll_votes);

// Mengambil data Pengumuman
$pengumuman_sql = "SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5";
$pengumuman_result = $conn->query($pengumuman_sql);
if (!$pengumuman_result) {
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil data Berita Lama yang Hangat
$berita_lama_sql = "SELECT * FROM berita ORDER BY kunjungan DESC LIMIT 5";
$berita_lama_result = $conn->query($berita_lama_sql);
if (!$berita_lama_result) {
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Mengambil data Logo untuk Running Image Logo
$logo_sql = "SELECT * FROM galeri WHERE jenis = 'logo' ORDER BY id DESC LIMIT 5";
$logo_result = $conn->query($logo_sql);
if (!$logo_result) {
    error_log("Query failed: (" . $conn->errno . ") " . $conn->error);
}
?>

<?php include 'includes/header.php'; ?>

<!-- Cover Image Section -->
<?php if ($cover_result && $cover_result->num_rows > 0): ?>
    <section class="cover-image mb-5">
        <?php
            $cover = $cover_result->fetch_assoc();
            echo "<img src='" . htmlspecialchars($cover['file_path']) . "' alt='Cover Image' class='img-fluid w-100' style='max-height: 500px; object-fit: cover;'>";
        ?>
    </section>
<?php endif; ?>

<!-- Section Pencarian dan Kategori -->
<div class="container mb-5">
    <div class="row">
        <!-- BERITA DPPKB di Tengah -->
        <div class="col-md-9">
            <h2 class="text-center mb-4">BERITA DPPKB</h2>
            <div class="row">
                <?php
                // Memeriksa apakah ada hasil query berita
                $berita_limit = 4;
                $berita_counter = 0;
                if ($result && $result->num_rows > 0) {
                    // Loop untuk menampilkan setiap berita
                    while ($row = $result->fetch_assoc()) {
                        if ($berita_counter >= $berita_limit) break;
                        echo "<div class='col-md-6 mb-4'>"; // Menampilkan dua berita per baris di medium dan lebih besar
                        echo "<div class='card h-100'>";
    
                        // Cek apakah file gambar ada dan valid
                        if (!empty($row['gambar']) && file_exists(__DIR__ . '/' . $row['gambar'])) { // Menyesuaikan path
                            // Menampilkan gambar jika ada
                            echo "<img src='" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar Berita'>";
                        } else {
                            // Menampilkan placeholder jika gambar tidak ada
                            echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image'>";
                        }
    
                        // Isi berita
                        echo "<div class='card-body d-flex flex-column'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($row['judul']) . "</h5>";
                        echo "<p class='card-text'>" . nl2br(htmlspecialchars(substr($row['konten'], 0, 100))) . "...</p>"; // Memotong konten untuk preview
                        echo "<p class='text-muted mt-auto'>Tanggal Publikasi: " . htmlspecialchars(date("d M Y", strtotime($row['tanggal_publikasi']))) . "</p>";
                        echo "<a href='detail_berita.php?id=" . urlencode($row['id']) . "' class='btn btn-primary mt-2'>Baca Selengkapnya</a>";
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
                <a href="berita.php" class="btn btn-secondary">Berita Selengkapnya</a>
            </div>
        </div>
        
        <!-- Kategori di Kanan -->
        <div class="col-md-3">
            <h4 class="text-center mb-3">Semua Kategori</h4>
            <div class="row">
                <?php
                // Query untuk menampilkan kategori
                $kategori_sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
                $kategori_result = $conn->query($kategori_sql);
    
                if ($kategori_result && $kategori_result->num_rows > 0) {
                    $kategori_per_row = 3; // Jumlah kategori per baris
                    $current_col = 0;
                    while ($row_kategori = $kategori_result->fetch_assoc()) {
                        echo "<div class='col-4 mb-3'>";
                        echo "<a href='index.php?kategori=" . urlencode($row_kategori['nama_kategori']) . "' class='btn btn-sm btn-outline-primary btn-block'>" . htmlspecialchars($row_kategori['nama_kategori']) . "</a>";
                        echo "</div>";
                        $current_col++;
                        if ($current_col % $kategori_per_row == 0) {
                            echo "</div><div class='row'>";
                        }
                    }
                } else {
                    echo "<p class='text-muted'>Tidak ada kategori.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<hr>

<!-- Section Informasi Terkini -->
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

<!-- Galeri Section -->
<section class="galeri-section mb-5" id="galeri">
    <div class="container">
        <h2 class="mb-4 text-center">Galeri</h2>
        <div class="row galeri-images">
            <?php
            if ($galeri_result && $galeri_result->num_rows > 0) {
                while ($galeri = $galeri_result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-3'>";
                    echo "<a href='" . htmlspecialchars($galeri['file_path']) . "' target='_blank'>";
                    echo "<img src='" . htmlspecialchars($galeri['file_path']) . "' alt='Galeri Image' class='img-thumbnail'>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center text-muted'>Tidak ada gambar di galeri.</p>";
            }
            ?>
        </div>
    </div>
</section>

<!-- Kontak Section dan Statistik Pengunjung dengan Jajak Pendapat -->
<section class="statistik-section section mb-5" id="kontak" style="background-color: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <h2 class="text-center mb-4">Kontak Kami & Statistik Pengunjung</h2>
        <div class="row">
            <!-- Kontak Kami -->
            <div class="col-md-6 mb-4">
                <h4>Kontak Kami</h4>
                <p><i class="fas fa-map-marker-alt"></i> Kantor Dinas PP & KB Kabupaten Muara Enim<br>
                Jl. Jend. A.K Gani No. 99 Kel. Tungkal, Kec. Muara Enim, Kabupaten Muara Enim, Sumatera Selatan 31313</p>
                <p><i class="fas fa-phone"></i> Telp. (0734) 421001<br>
                <i class="fas fa-fax"></i> Fax. -<br>
                <i class="fas fa-envelope"></i> Email. <a href="mailto:dppkbmuaraenim@gmail.com">dppkbmuaraenim@gmail.com</a></p>
            </div>
            <!-- Statistik Pengunjung dan Jajak Pendapat -->
            <div class="col-md-6">
                <h4>Statistik Pengunjung</h4>
                <ul class="list-unstyled">
                    <li><i class="fas fa-users"></i> Pengunjung Terbanyak: <?php echo htmlspecialchars($top_pengunjung['halaman']); ?> (<?php echo htmlspecialchars($top_pengunjung['kunjungan']); ?> kunjungan)</li>
                    <li><i class="fas fa-chart-line"></i> Total Hit: <?php echo htmlspecialchars($statistik['total_hit']); ?></li>
                    <li><i class="fas fa-calendar-day"></i> Hari Ini: <?php echo htmlspecialchars($statistik['hari_ini']); ?></li>
                    <li><i class="fas fa-calendar-minus"></i> Kemarin: <?php echo htmlspecialchars($statistik['kemarin']); ?></li>
                </ul>

                <!-- Jajak Pendapat -->
                <div class="poll-container mt-4">
                    <h4>Jajak Pendapat</h4>
                    <form method="POST" action="index.php#kontak">
                        <p>Menurut pengunjung, apakah isi website ini bersifat informatif?</p>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vote" id="ya" value="Ya" required>
                            <label class="form-check-label" for="ya">
                                Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vote" id="cukup" value="Cukup">
                            <label class="form-check-label" for="cukup">
                                Cukup
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vote" id="tidak" value="Tidak">
                            <label class="form-check-label" for="tidak">
                                Tidak
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="vote" id="tidak_tahu" value="Tidak Tahu">
                            <label class="form-check-label" for="tidak_tahu">
                                Tidak Tahu
                            </label>
                        </div>
                        <button type="submit" name="poll" class="btn btn-primary mt-3">Kirim</button>
                    </form>

                    <!-- Hasil Jajak Pendapat -->
                    <?php if ($total_votes > 0): ?>
                        <div class="mt-4">
                            <h5>Hasil Jajak Pendapat</h5>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo round(($poll_votes['Ya'] / $total_votes) * 100); ?>%;" aria-valuenow="<?php echo $poll_votes['Ya']; ?>" aria-valuemin="0" aria-valuemax="100">Ya (<?php echo $poll_votes['Ya']; ?>)</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo round(($poll_votes['Cukup'] / $total_votes) * 100); ?>%;" aria-valuenow="<?php echo $poll_votes['Cukup']; ?>" aria-valuemin="0" aria-valuemax="100">Cukup (<?php echo $poll_votes['Cukup']; ?>)</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo round(($poll_votes['Tidak'] / $total_votes) * 100); ?>%;" aria-valuenow="<?php echo $poll_votes['Tidak']; ?>" aria-valuemin="0" aria-valuemax="100">Tidak (<?php echo $poll_votes['Tidak']; ?>)</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo round(($poll_votes['Tidak Tahu'] / $total_votes) * 100); ?>%;" aria-valuenow="<?php echo $poll_votes['Tidak Tahu']; ?>" aria-valuemin="0" aria-valuemax="100">Tidak Tahu (<?php echo $poll_votes['Tidak Tahu']; ?>)</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Akhir Jajak Pendapat -->
            </div>
        </div>
    </div>
</section>

<!-- Modal Galeri -->
<div class="modal fade" id="galeriModal" tabindex="-1" aria-labelledby="galeriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="Detail Gambar" class="img-fluid" id="modalGambar">
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Galeri -->

<?php include 'includes/footer.php'; ?>

<?php
// Menutup koneksi database
$conn->close();
?>
