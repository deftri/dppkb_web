<?php
// includes/header.php

// Pastikan session telah dimulai di setiap halaman sebelum menyertakan header
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan halaman aktif jika belum ditentukan
if (!isset($halaman)) {
    $halaman = '';
}

// Set judul halaman
$page_title = ucfirst(str_replace(['.php', 'index'], '', basename($_SERVER['PHP_SELF'], ".php"))) . " | DPPKB Kabupaten Muara Enim";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="Website resmi Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim. Menyajikan informasi terkini dan relevan.">
    <meta name="keywords" content="Dinas Pengendalian Penduduk, Keluarga Berencana, Kabupaten Muara Enim, Berita, Galeri">
    <meta name="author" content="Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="css/header.css">
    <!-- Favicon (Opsional) -->
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Running Text dan Jam -->
    <div class="news-ticker bg-light text-dark py-2">
    <div class="container d-flex justify-content-between align-items-center">
    <div class="news-ticker bg-light text-dark py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="ticker-wrap">
            <p class="ticker-text mb-0">
                Selamat datang di website resmi Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim! Dapatkan informasi terkini dan terpercaya setiap hari.
            </p>
        </div>
        <div class="clock" id="clock"></div>
    </div>
</div>
    </div>
</div>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
        <img src="../assets/img/logodinas1.png" alt="Logo Dinas" height="60">

        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" 
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <!-- Menu Navigasi -->
                <li class="nav-item <?php echo ($halaman == 'index.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Beranda</a>
                </li>
                <li class="nav-item <?php echo ($halaman == 'tentang.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="tentang.php"><i class="fas fa-info-circle"></i> Tentang</a>
                </li>
                <li class="nav-item <?php echo ($halaman == 'renja.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="renja.php"><i class="fas fa-chart-line"></i> Renja</a>
                </li>
                <li class="nav-item <?php echo ($halaman == 'galeri.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="galeri.php"><i class="fas fa-images"></i> Galeri</a>
                </li>
                <li class="nav-item <?php echo ($halaman == 'berita.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="berita.php"><i class="fas fa-newspaper"></i> Berita</a>
                </li>

                <!-- Menu Sinderela yang Mencolok -->
                <li class="nav-item <?php echo ($halaman == 'sinderela.menu.php') ? 'active' : ''; ?>">
    <a class="nav-link sinderela-menu" href="../public/sinderela.menu.php">
        <i class="fas fa-file-alt"></i> SINDERELA
    </a>
</li>



                <li class="nav-item <?php echo ($halaman == 'kontak.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="kontak.php"><i class="fas fa-envelope"></i> Kontak</a>
                </li>

                <!-- Login/logout -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-user"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item <?php echo ($halaman == 'login.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


    <!-- Akhir Navbar -->

    <!-- Separator Navbar -->
    <div class="navbar-separator"></div>

    <!-- Nama Dinas Section -->
    <div class="nama-dinas text-center my-4">
        <h3 class="text-uppercase">Dinas Pengendalian Penduduk dan Keluarga Berencana</h3>
        <h4 class="text-uppercase">Kabupaten Muara Enim</h4>
    </div>

    <!-- Tambahkan Jam Digital -->
    <script>
        function updateClock() {
            const clockElement = document.getElementById('clock');
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock(); // Initial call
    </script>

    <!-- Dark Mode Script -->
