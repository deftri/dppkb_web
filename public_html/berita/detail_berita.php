<?php
// detail_berita.php
include '../config/config.php'; // Ganti path sesuai lokasi file config Anda

// Memulai sesi
session_start();
session_regenerate_id(true);

// Validasi ID berita dari parameter GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    echo "<div class='alert alert-warning text-center mt-5'>ID berita tidak valid.</div>";
    exit();
}

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

// Update view count
$stmt_view = $conn->prepare("UPDATE berita SET view_count = view_count + 1 WHERE id = ?");
if ($stmt_view) {
    $stmt_view->bind_param("i", $id);
    $stmt_view->execute();
    $stmt_view->close();
} else {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Query berita berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM berita WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    echo "<div class='alert alert-danger text-center mt-5'>Terjadi kesalahan saat mengambil data berita.</div>";
    exit();
}

// Memeriksa apakah berita ditemukan
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger text-center mt-5'>Berita tidak ditemukan.</div>";
    exit();
}
$stmt->close();

// Mengambil data berita populer
$sql_populer = "SELECT id, judul FROM berita ORDER BY view_count DESC LIMIT 5";
$result_populer = $conn->query($sql_populer);
$berita_populer = [];
if ($result_populer && $result_populer->num_rows > 0) {
    while ($row_populer = $result_populer->fetch_assoc()) {
        $berita_populer[] = $row_populer;
    }
}

// Mengambil data komentar
$stmt_komentar = $conn->prepare("SELECT nama, tanggal, isi FROM komentar WHERE berita_id = ? ORDER BY tanggal DESC");
if ($stmt_komentar) {
    $stmt_komentar->bind_param("i", $id);
    $stmt_komentar->execute();
    $result_komentar = $stmt_komentar->get_result();
} else {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    $result_komentar = false;
}

// Mengambil data berita terkait
$kategori = $row['kategori'];
$id_berita = $row['id'];
$sql_terkait = "SELECT id, judul FROM berita WHERE kategori = ? AND id != ? ORDER BY tanggal_publikasi DESC LIMIT 3";
$stmt_terkait = $conn->prepare($sql_terkait);
if ($stmt_terkait) {
    $stmt_terkait->bind_param("si", $kategori, $id_berita);
    $stmt_terkait->execute();
    $result_terkait = $stmt_terkait->get_result();
    $berita_terkait = [];
    while ($berita = $result_terkait->fetch_assoc()) {
        $berita_terkait[] = $berita;
    }
    $stmt_terkait->close();
} else {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    $berita_terkait = [];
}

// Mengambil data polling
$poll_result = $conn->query("SELECT vote, COUNT(*) as count FROM poll_votes GROUP BY vote");
$poll_votes = ['Ya' => 0, 'Cukup' => 0, 'Tidak' => 0, 'Tidak Tahu' => 0];
if ($poll_result) {
    while ($row_poll = $poll_result->fetch_assoc()) {
        if (array_key_exists($row_poll['vote'], $poll_votes)) {
            $poll_votes[$row_poll['vote']] = $row_poll['count'];
        }
    }
}
$total_votes = array_sum($poll_votes);

// Memproses komentar jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_komentar'])) {
    $nama = trim($_POST['nama']);
    $isi = trim($_POST['isi']);

    if (!empty($nama) && !empty($isi)) {
        $stmt_insert_komentar = $conn->prepare("INSERT INTO komentar (berita_id, nama, tanggal, isi) VALUES (?, ?, NOW(), ?)");
        if ($stmt_insert_komentar) {
            $stmt_insert_komentar->bind_param("iss", $id, $nama, $isi);
            $stmt_insert_komentar->execute();
            $stmt_insert_komentar->close();
            // Redirect untuk menghindari pengisian ulang form
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $error_komentar = "Terjadi kesalahan saat mengirim komentar.";
        }
    } else {
        $error_komentar = "Nama dan komentar tidak boleh kosong.";
    }
}

// Memproses rating jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    if ($rating >= 1 && $rating <= 5) {
        $stmt_insert_rating = $conn->prepare("INSERT INTO ratings (berita_id, rating, tanggal) VALUES (?, ?, NOW())");
        if ($stmt_insert_rating) {
            $stmt_insert_rating->bind_param("ii", $id, $rating);
            $stmt_insert_rating->execute();
            $stmt_insert_rating->close();
            // Redirect untuk menghindari pengisian ulang form
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $error_rating = "Terjadi kesalahan saat mengirim rating.";
        }
    } else {
        $error_rating = "Rating harus antara 1 hingga 5.";
    }
}

// Mengambil rata-rata rating
$avg_rating = 0;
$count_rating = 0;
$stmt_avg = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS count_rating FROM ratings WHERE berita_id = ?");
if ($stmt_avg) {
    $stmt_avg->bind_param("i", $id);
    $stmt_avg->execute();
    $stmt_avg->bind_result($avg_rating, $count_rating);
    $stmt_avg->fetch();
    $stmt_avg->close();
} else {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']) . " - Situs Berita"; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($row['konten']), 0, 150)); ?>...">
    <meta name="keywords" content="berita, <?php echo htmlspecialchars($kategori); ?>">
    <meta name="author" content="Nama Situs">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .related-article h4 a {
            text-decoration: none;
            color: #007bff;
        }
        .related-article h4 a:hover {
            text-decoration: underline;
        }
        .poll-container .progress {
            height: 25px;
        }
        .poll-container .progress-bar {
            line-height: 25px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <!-- Detail Berita -->
        <div class="row">
            <div class="col-md-8">
                <h1><?php echo htmlspecialchars($row['judul']); ?></h1>
                <p class="text-muted">Tanggal Publikasi: <?php echo htmlspecialchars(date("d M Y", strtotime($row['tanggal_publikasi']))); ?> | Kategori: <?php echo htmlspecialchars($row['kategori']); ?> | Dibaca: <?php echo htmlspecialchars($row['view_count']); ?> kali</p>
                
                <!-- Gambar Berita -->
                <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/' . $row['gambar'])): ?>
                    <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Berita" class="img-fluid mb-4">
                <?php else: ?>
                    <img src="https://via.placeholder.com/800x400.png?text=No+Image" alt="No Image" class="img-fluid mb-4">
                <?php endif; ?>

                <!-- Konten Berita -->
                <div class="berita-konten">
                    <?php echo nl2br(htmlspecialchars($row['konten'])); ?>
                </div>

                <!-- Bookmark (Hanya untuk user yang login) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mt-4">
                        <form method="POST" action="proses_bookmark.php">
                            <input type="hidden" name="berita_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <?php
                            // Cek apakah sudah di-bookmark
                            $user_id = $_SESSION['user_id'];
                            $stmt_check = $conn->prepare("SELECT 1 FROM bookmarks WHERE user_id = ? AND berita_id = ?");
                            if ($stmt_check) {
                                $stmt_check->bind_param("ii", $user_id, $id);
                                $stmt_check->execute();
                                $stmt_check->store_result();
                                if ($stmt_check->num_rows > 0) {
                                    // Sudah di-bookmark
                                    echo "<button type='submit' name='action' value='unbookmark' class='btn btn-danger'><i class='fas fa-bookmark'></i> Hapus Bookmark</button>";
                                } else {
                                    // Belum di-bookmark
                                    echo "<button type='submit' name='action' value='bookmark' class='btn btn-primary'><i class='far fa-bookmark'></i> Bookmark</button>";
                                }
                                $stmt_check->close();
                            }
                            ?>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Rating -->
                

                <!-- Form Tambahkan Komentar -->
                <div class="mt-4">
                    <h3>Tambahkan Komentar:</h3>
                    <form method="POST" action="detail_berita.php?id=<?php echo htmlspecialchars($id); ?>" class="mb-4">
                        <div class="form-group">
                            <label for="nama">Nama:</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="isi">Komentar:</label>
                            <textarea name="isi" id="isi" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit_komentar" class="btn btn-primary">Kirim Komentar</button>
                        <?php if (isset($error_komentar)): ?>
                            <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($error_komentar); ?></div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Menampilkan Komentar -->
                <div class="mt-4">
                    <h3>Komentar:</h3>
                    <?php
                    if ($result_komentar && $result_komentar->num_rows > 0) {
                        while ($komentar = $result_komentar->fetch_assoc()) {
                            echo "<div class='card mb-3'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($komentar['nama']) . "</h5>";
                            echo "<h6 class='card-subtitle mb-2 text-muted'>" . htmlspecialchars(date("d M Y H:i", strtotime($komentar['tanggal']))) . "</h6>";
                            echo "<p class='card-text'>" . nl2br(htmlspecialchars($komentar['isi'])) . "</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p class='text-muted'>Belum ada komentar.</p>";
                    }
                    ?>
                </div>

                <!-- Berita Terkait -->
                <div class="mt-4">
                    <h3>Berita Terkait</h3>
                    <div class="row">
                        <?php
                        if (!empty($berita_terkait)) {
                            foreach ($berita_terkait as $berita) {
                                echo "<div class='col-md-4 mb-3'>";
                                echo "<div class='card h-100'>";
                                // Gambar Berita Terkait
                                $sql_gambar_terkait = "SELECT gambar FROM berita WHERE id = ?";
                                $stmt_gambar = $conn->prepare($sql_gambar_terkait);
                                if ($stmt_gambar) {
                                    $stmt_gambar->bind_param("i", $berita['id']);
                                    $stmt_gambar->execute();
                                    $result_gambar = $stmt_gambar->get_result();
                                    if ($result_gambar && $result_gambar->num_rows > 0) {
                                        $gambar = $result_gambar->fetch_assoc()['gambar'];
                                        if (!empty($gambar) && file_exists(__DIR__ . '/' . $gambar)) {
                                            echo "<img src='" . htmlspecialchars($gambar) . "' class='card-img-top' alt='Gambar Berita Terkait'>";
                                        } else {
                                            echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image'>";
                                        }
                                    } else {
                                        echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image'>";
                                    }
                                    $stmt_gambar->close();
                                } else {
                                    echo "<img src='https://via.placeholder.com/400x200.png?text=No+Image' class='card-img-top' alt='No Image'>";
                                }

                                echo "<div class='card-body d-flex flex-column'>";
                                echo "<h5 class='card-title'>" . htmlspecialchars($berita['judul']) . "</h5>";
                                echo "<a href='detail_berita.php?id=" . htmlspecialchars($berita['id']) . "' class='mt-auto btn btn-primary'>Baca Selengkapnya</a>";
                                echo "</div></div></div>";
                            }
                        } else {
                            echo "<div class='col-12'><p class='text-muted'>Tidak ada berita terkait.</p></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Berita Populer) -->
            <div class="col-md-4">
                <h3>Berita Populer</h3>
                <ul class="list-group">
                    <?php
                    if (!empty($berita_populer)) {
                        foreach ($berita_populer as $populer) {
                            echo "<li class='list-group-item'><a href='detail_berita.php?id=" . htmlspecialchars($populer['id']) . "'>" . htmlspecialchars($populer['judul']) . "</a></li>";
                        }
                    } else {
                        echo "<li class='list-group-item text-muted'>Tidak ada berita populer.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Polling -->
        <div class="mt-5">
            <h3>Jajak Pendapat</h3>
            <p>Menurut pengunjung, apakah isi website ini bersifat informatif?</p>
            <form method="POST" action="detail_berita.php?id=<?php echo htmlspecialchars($id); ?>">
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
                <button type="submit" name="submit_poll" class="btn btn-primary mt-3">Kirim</button>
            </form>

            <!-- Hasil Polling -->
            <?php if ($total_votes > 0): ?>
                <div class="mt-4">
                    <h5>Hasil Polling</h5>
                    <?php
                    foreach ($poll_votes as $option => $count) {
                        $percentage = ($total_votes > 0) ? round(($count / $total_votes) * 100) : 0;
                        $color = '';
                        switch ($option) {
                            case 'Ya':
                                $color = 'bg-success';
                                break;
                            case 'Cukup':
                                $color = 'bg-info';
                                break;
                            case 'Tidak':
                                $color = 'bg-warning';
                                break;
                            case 'Tidak Tahu':
                                $color = 'bg-danger';
                                break;
                            default:
                                $color = 'bg-secondary';
                        }
                        echo "<p><strong>" . htmlspecialchars($option) . ":</strong> " . htmlspecialchars($count) . " (" . htmlspecialchars($percentage) . "%)</p>";
                        echo "<div class='progress mb-2'>";
                        echo "<div class='progress-bar " . $color . "' role='progressbar' style='width: " . htmlspecialchars($percentage) . "%;' aria-valuenow='" . htmlspecialchars($percentage) . "' aria-valuemin='0' aria-valuemax='100'>" . htmlspecialchars($percentage) . "%</div>";
                        echo "</div>";
                    }
                    ?>

                    
                </div>
                <div class="mt-4">
                    <h4>Rating: <?php echo $avg_rating > 0 ? round($avg_rating, 1) : "Belum ada rating"; ?> / 5</h4>
                    <p>Total Rating: <?php echo htmlspecialchars($count_rating); ?></p>
                    <!-- Form Beri Rating -->
                    <form method="POST" action="detail_berita.php?id=<?php echo htmlspecialchars($id); ?>" class="mb-4">
                        <div class="form-group">
                            <label for="rating">Beri Rating:</label>
                            <select name="rating" id="rating" class="form-control w-25" required>
                                <option value="" disabled selected>Pilih Rating</option>
                                <option value="1">1 - Sangat Buruk</option>
                                <option value="2">2 - Buruk</option>
                                <option value="3">3 - Cukup</option>
                                <option value="4">4 - Baik</option>
                                <option value="5">5 - Sangat Baik</option>
                            </select>
                        </div>
                        <button type="submit" name="submit_rating" class="btn btn-success">Kirim Rating</button>
                        <?php if (isset($error_rating)): ?>
                            <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($error_rating); ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Modal Galeri (Jika diperlukan) -->
    <!--
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
    -->
</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>
