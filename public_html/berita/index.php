<?php
// index.php
session_start();
include '../config/config.php'; // Ganti path sesuai lokasi file config Anda
include 'includes/header.php';




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
        $update_statistik = $conn->prepare("UPDATE statistik SET kunjungan = kunjungan + 1 WHERE halaman = ? AND tanggal = ?");
        if ($update_statistik) {
            $update_statistik->bind_param("ss", $halaman, $tanggal);
            $update_statistik->execute();
            $update_statistik->close();
        }
    } else {
        $insert_statistik = $conn->prepare("INSERT INTO statistik (halaman, kunjungan, tanggal) VALUES (?, 1, ?)");
        if ($insert_statistik) {
            $insert_statistik->bind_param("ss", $halaman, $tanggal);
            $insert_statistik->execute();
            $insert_statistik->close();
        }
    }
    $check_statistik->close();
}

// Setup Pencarian dan Kategori
$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Pagination setup
$limit = 6; // Jumlah berita per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($keyword) {
    // Jika ada pencarian berdasarkan kata kunci (judul atau konten)
    $sql = "SELECT * FROM berita WHERE judul LIKE CONCAT('%', ?, '%') OR konten LIKE CONCAT('%', ?, '%') ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $keyword, $keyword, $limit, $offset);
} elseif ($kategori_filter) {
    // Jika ada filter berdasarkan kategori
    $sql = "SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $kategori_filter, $limit, $offset);
} else {
    // Jika tidak ada pencarian atau filter kategori, ambil semua berita
    $sql = "SELECT * FROM berita ORDER BY tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Hitung total berita untuk pagination (perbaiki untuk menghitung jumlah berita berdasarkan query yang diterapkan)
if ($keyword || $kategori_filter) {
    // Jika ada pencarian atau filter berdasarkan kategori, hitung berdasarkan kondisi yang dipilih
    if ($keyword) {
        $count_sql = "SELECT COUNT(*) AS total FROM berita WHERE judul LIKE CONCAT('%', ?, '%') OR konten LIKE CONCAT('%', ?, '%')";
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("ss", $keyword, $keyword);
    } elseif ($kategori_filter) {
        $count_sql = "SELECT COUNT(*) AS total FROM berita WHERE kategori = ?";
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("s", $kategori_filter);
    }
} else {
    // Jika tidak ada pencarian atau filter kategori, hitung semua berita
    $count_sql = "SELECT COUNT(*) AS total FROM berita";
    $count_stmt = $conn->prepare($count_sql);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);


// Mengambil data Galeri
$galeri_sql = "SELECT * FROM galeri ORDER BY id DESC LIMIT 9";
$galeri_result = $conn->query($galeri_sql);

// Mengambil data Cover Image
$cover_sql = "SELECT * FROM galeri WHERE jenis = 'cover' ORDER BY id DESC LIMIT 1";
$cover_result = $conn->query($cover_sql);

// Mengambil data statistik
$statistik_sql = "SELECT SUM(kunjungan) AS total_hit, 
                         SUM(CASE WHEN tanggal = CURDATE() THEN kunjungan ELSE 0 END) AS hari_ini,
                         SUM(CASE WHEN tanggal = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN kunjungan ELSE 0 END) AS kemarin 
                  FROM statistik";
$statistik_result = $conn->query($statistik_sql);
$statistik = $statistik_result->fetch_assoc();

// Mengambil data polling
$poll_sql = "SELECT vote, COUNT(*) AS count FROM poll GROUP BY vote";
$poll_result = $conn->query($poll_sql);

$poll_votes = [];
while ($poll = $poll_result->fetch_assoc()) {
    $poll_votes[$poll['vote']] = $poll['count'];
}

// Hitung total suara
$total_votes = array_sum($poll_votes);

// Mengambil pengunjung terbanyak
$top_pengunjung_sql = "SELECT halaman, kunjungan FROM statistik ORDER BY kunjungan DESC LIMIT 1";
$top_pengunjung_result = $conn->query($top_pengunjung_sql);
$top_pengunjung = $top_pengunjung_result->fetch_assoc();

// Mengambil data pengumuman
$pengumuman_sql = "SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5"; // Sesuaikan dengan kebutuhan
$pengumuman_result = $conn->query($pengumuman_sql);

if (!$pengumuman_result) {
    die("Query failed: " . $conn->error);
}

// Mengambil berita lama yang hangat
$berita_lama_sql = "SELECT * FROM berita WHERE tanggal_publikasi < CURDATE() ORDER BY tanggal_publikasi DESC LIMIT 3"; // Sesuaikan dengan kebutuhan
$berita_lama_result = $conn->query($berita_lama_sql);

if (!$berita_lama_result) {
    die("Query failed: " . $conn->error);
}
?>


<!-- Cover Image Section -->
<?php if ($cover_result && $cover_result->num_rows > 0): ?>
    <section class="cover-image mb-5">
        <?php
            $cover = $cover_result->fetch_assoc();
            echo "<img src='../" . htmlspecialchars($cover['file_path']) . "' alt='Cover Image' class='img-fluid cover-img' />";
        ?>
    </section>

    <style>
        /* Styling untuk cover image */
        .cover-image {
            width: 100%;
            height: auto;
            overflow: hidden;
            position: relative;
            padding: 20px 0;
        }

        /* Gambar cover dengan tampilan menarik */
        .cover-img {
            width: 100%;
            height: 500px;
            object-fit: cover; /* Memastikan gambar mengisi seluruh area dengan proporsi yang benar */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Efek hover untuk gambar */
        .cover-img:hover {
            transform: scale(1.05); /* Memperbesar gambar sedikit saat di-hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Responsif untuk perangkat dengan lebar layar kecil seperti tablet atau ponsel */
        @media (max-width: 768px) {
            .cover-img {
                height: 300px; /* Mengurangi tinggi gambar pada perangkat kecil */
            }
        }

        /* Tambahkan gaya khusus pada gambar jika diinginkan */
        .cover-image {
            background-color: #f4f4f4; /* Memberikan latar belakang netral */
            border-radius: 10px;
            padding: 20px;
        }
    </style>
<?php endif; ?>

<hr>
<!-- Berita Section -->
<?php 
include "beritautama.php";
?>
<hr>
<?php 
include "galeriutama.php";
?>
<hr>
<?php 
include "pengumumanutama.php";
?>
<hr>

<section class="kontak-statistik mb-5" id="kontak" style="background-color: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <h2 class="text-center mb-4">Statistik Pengunjung & Jajak Pendapat</h2>
        <div class="row justify-content-center">
            <!-- Kolom Statistik Pengunjung -->
            <div class="col-lg-6 col-md-8 col-12 mb-4">
                <h4>Statistik Pengunjung</h4>
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Kunjungan</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['total_hit']); ?></h5>
                    </div>
                </div>
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Kunjungan Hari Ini</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['hari_ini']); ?></h5>
                    </div>
                </div>
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Kunjungan Kemarin</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['kemarin']); ?></h5>
                    </div>
                </div>
            </div>

            <!-- Kolom Jajak Pendapat -->
            <div class="col-lg-6 col-md-8 col-12">
                <div class="poll-container">
                    <h4>Jajak Pendapat</h4>
                    <form method="POST" action="index.php#kontak">
                        <div class="form-group">
                            <p>Menurut Anda, apakah isi website ini bermanfaat?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="ya" value="Ya" required>
                                <label class="form-check-label" for="ya">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="cukup" value="Cukup">
                                <label class="form-check-label" for="cukup">Cukup</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="tidak" value="Tidak">
                                <label class="form-check-label" for="tidak">Tidak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="tidak_tahu" value="Tidak Tahu">
                                <label class="form-check-label" for="tidak_tahu">Tidak Tahu</label>
                            </div>
                        </div>
                        <button type="submit" name="poll" class="btn btn-primary w-100 mt-3">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Styling untuk statistik pengunjung */
        .card-body h5 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* Styling untuk form jajak pendapat */
        .poll-container form .form-check-label {
            font-weight: 500;
        }

        /* Styling untuk form jajak pendapat agar lebih responsif */
        .poll-container button {
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .poll-container button:hover {
            background-color: #0056b3;
        }

        /* Pastikan form jajak pendapat responsif */
        .poll-container .form-check {
            margin-bottom: 1rem;
        }

        .poll-container form {
            margin-top: 20px;
        }
        hr {
            border: 0;
            height: 2px;
            background-color: #007bff;
            margin: 20px 0;
            transition: all 0.3s ease; /* Efek transisi */
        }

        hr:hover {
            background-color: #00c6ff; /* Ubah warna saat hover */
            height: 3px; /* Perbesar ketebalan saat hover */
        }
    </style>
</section>

<p>
    <br>
    
    <br>
    
</p>


<?php 
//include "statistikutama.php";
include 'includes/footer.php';
?>


