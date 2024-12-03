<?php
// includes/header.php

// Pastikan session telah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan halaman aktif jika belum ditentukan
$halaman = $halaman ?? ''; // PHP 7+ shorthand for checking if variable is set

// Set judul halaman
$page_title = ucfirst(str_replace(['.php', 'index'], '', basename($_SERVER['PHP_SELF'], ".php"))) . " | DPPKB Kabupaten Muara Enim";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Website resmi Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim. Menyajikan informasi terkini dan relevan.">
    <meta name="keywords" content="Dinas Pengendalian Penduduk, Keluarga Berencana, Kabupaten Muara Enim, Berita, Galeri">
    <meta name="author" content="Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim">

    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/header.css">
    
    <!-- Add custom CSS for running text -->
    <style>
    /* Animasi Running Text */
    .running-text {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        box-sizing: border-box;
        position: relative;
        background-color: #007bff;
        color: white;
        padding: 15px 0;
    }

    .running-text p {
        display: inline-block;
        padding-left: 100%;
        animation: slide 40s linear infinite; /* Mengubah durasi menjadi 30 detik */
    }

    /* Animasi untuk teks bergerak */
    @keyframes slide {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
</style>

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Logo atau Brand -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/img/logodinas1.png" alt="Logo" style="width: 50px; height: auto;">
                <span class="ms-2">DPPKB Kabupaten Muara Enim</span>
            </a>

            <!-- Tombol Toggle (untuk menu responsif) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navigasi -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'home' ? 'active' : ''); ?>" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'tentang' ? 'active' : ''); ?>" href="tentang.php">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'renja' ? 'active' : ''); ?>" href="renja.php">Renja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'program' ? 'active' : ''); ?>" href="program.php">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'berita' ? 'active' : ''); ?>" href="kumpulan_berita.php">Berita</a>
                    </li>
                    <li class="nav-item layanan">
                        <a class="nav-link <?php echo ($halaman == 'layanan' ? 'active' : ''); ?>" href="../public/sinderela.menu.php">
                            <img src="images/navsinderela.png" alt="Sinderela" style="width: 100px; height: auto;">
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'galeri' ? 'active' : ''); ?>" href="galeri.php">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'kontak' ? 'active' : ''); ?>" href="kontak.php">Kontak</a>
                    </li>
                    <!-- Login / Logout -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Running Text Section -->
    <section class="running-text">
        <div class="container">
            <p>Selamat datang di Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim. Kami berkomitmen untuk meningkatkan kesejahteraan keluarga dan pengendalian penduduk yang berkelanjutan. Dapatkan informasi terbaru melalui layanan kami!</p>
        </div>
    </section>

    <!-- Tambahkan script Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
