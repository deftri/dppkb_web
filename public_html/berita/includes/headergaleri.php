<?php
// includes/headersgaleri.php

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="Website resmi Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim. Menyajikan informasi terkini dan relevan.">
    <meta name="keywords" content="Dinas Pengendalian Penduduk, Keluarga Berencana, Kabupaten Muara Enim, Berita, Galeri">
    <meta name="author" content="Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/header.css">
    
    <!-- Additional Styles for Gallery Page -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Styling */
        nav.navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .navbar-brand img {
            width: 50px;
            height: auto;
        }

        .navbar-nav .nav-item .nav-link.active {
            color: #007bff;
            font-weight: 600;
        }

        /* Jumbotron Styling */
        .jumbotron {
            background: #007bff url('../assets/img/galeri_banner.jpg') center/cover no-repeat;
            color: #fff;
            padding: 80px 20px;
            text-align: center;
        }

        .jumbotron h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .jumbotron p {
            font-size: 1.25rem;
            margin-top: 20px;
        }

        /* Gallery Section */
        .gallery-section {
            margin-top: 40px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item .caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 10px;
            font-size: 1.25rem;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .caption {
            opacity: 1;
        }

        /* Footer Styling */
        footer {
            background-color: #343a40;
            color: #fff;
            padding: 30px 0;
        }

        footer .container {
            max-width: 1200px;
        }

        footer .social-icons a {
            color: #fff;
            margin: 0 10px;
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .gallery-item .caption {
                font-size: 1rem;
            }

            .jumbotron h1 {
                font-size: 2rem;
            }

            .jumbotron p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Logo atau Brand -->
            <a class="navbar-brand" href="index.php">
                <img src="../assets/img/logodinas1.png" alt="Logo">
                DPPKB Kabupaten Muara Enim
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
                        <a class="nav-link <?php echo ($halaman == 'layanan' ? 'active' : ''); ?>" href="layanan.php">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'galeri' ? 'active' : ''); ?>" href="galeri.php">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman == 'kontak' ? 'active' : ''); ?>" href="kontak.php">Kontak</a>
                    </li>
                    <!-- Jika login diperlukan -->
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

    <!-- Jumbotron Section -->
    <div class="jumbotron">
        <h1>Selamat datang di Galeri DPPKB Kabupaten Muara Enim</h1>
        <p>Menyajikan foto-foto terkini terkait pengendalian penduduk dan keluarga berencana di Kabupaten Muara Enim.</p>
        <a href="#galeri" class="btn btn-light btn-lg">Lihat Galeri</a>
    </div>

    <!-- Galeri Section -->
    <div class="gallery-section container mt-5">
    <h2 class="text-center mb-4">Galeri Foto</h2>
    <div class="row">
        <?php
        // Mengecek apakah ada gambar di database
        if ($galeri_result && $galeri_result->num_rows > 0) {
            // Looping untuk menampilkan gambar
            while ($galeri_item = $galeri_result->fetch_assoc()) {
                $galeri_image_path = htmlspecialchars($galeri_item['file_path']);
                $galeri_caption = htmlspecialchars($galeri_item['caption']);
        ?>
            <div class="col-md-4 mb-4">
                <div class="gallery-item">
                    <a href="detail_gambar.php?id=<?php echo $galeri_item['id']; ?>">
                        <img src="../<?php echo $galeri_image_path; ?>" alt="<?php echo $galeri_caption; ?>" class="img-fluid">
                        <div class="caption"><?php echo $galeri_caption; ?></div>
                    </a>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p class='text-center text-muted'>Tidak ada gambar di galeri.</p>";
        }
        ?>
    </div>
</div>

    <!-- Footer (Optional) -->
    <footer>
        <div class="container text-center">
            <p>&copy; 2024 DPPKB Kabupaten Muara Enim. All Rights Reserved.</p>
            <div class="social-icons">
                <a href="#" class="fab fa-facebook"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
            </div>
        </div>
    </footer>

    <!-- Tambahkan script Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
