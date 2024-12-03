<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background-color: #007bff;
            color: white;
            padding: 80px 0;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
        }
        .hero-section p {
            font-size: 1.2rem;
            font-weight: 300;
        }
        .content-section {
            padding: 60px 0;
        }
        .content-section h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #343a40;
        }
        .content-section p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .vision-mission {
            background-color: #e9ecef;
            padding: 40px 0;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .team-member h5 {
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<?php 
include "includes/header.php"
?>

<!-- About Us Section -->
<section class="content-section">
    <div class="container">
        <h2 class="text-center mb-4">Tentang Kami</h2>
        <p class="lead text-center">Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim memiliki peran penting dalam pengelolaan pengendalian penduduk dan peningkatan kualitas keluarga melalui berbagai program yang bertujuan untuk menciptakan keluarga yang sejahtera dan berdaya saing.</p>
        <p>Melalui program-program Keluarga Berencana (KB), kami berkomitmen untuk membantu masyarakat dalam mengelola pertumbuhan penduduk, serta memberikan edukasi tentang pentingnya kesejahteraan keluarga dan perencanaan keluarga yang sehat.</p>
    </div>
</section>

<!-- Vision and Mission Section -->
<section class="vision-mission">
    <div class="container text-center">
        <h2>Visi & Misi</h2>
        <p><strong>Visi:</strong> Terwujudnya pengendalian jumlah penduduk yang berkualitas, melalui peningkatan kesejahteraan keluarga dan keseimbangan pembangunan di Kabupaten Muara Enim.</p>
        <p><strong>Misi:</strong> 
            <ul class="list-unstyled">
                <li>1. Meningkatkan kualitas hidup keluarga melalui program Keluarga Berencana.</li>
                <li>2. Menyediakan informasi dan edukasi mengenai pengendalian jumlah penduduk.</li>
                <li>3. Mendorong peningkatan partisipasi masyarakat dalam program KB.</li>
                <li>4. Meningkatkan koordinasi antar lembaga terkait dalam pengendalian penduduk.</li>
            </ul>
        </p>
    </div>
</section>

<!-- Our Services Section -->
<section class="content-section">
    <div class="container">
        <h2 class="text-center mb-4">Layanan Kami</h2>
        <p class="lead text-center">Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim menyediakan berbagai layanan untuk membantu masyarakat dalam perencanaan keluarga, termasuk layanan informasi KB, konseling, dan pemeriksaan kesehatan reproduksi. Berikut adalah beberapa layanan unggulan kami:</p>
        <div class="row">
            <!-- Layanan 1 -->
            <div class="col-md-4 text-center">
                <h5>Layanan Keluarga Berencana</h5>
                <p>Menyediakan informasi tentang metode kontrasepsi dan pilihan keluarga berencana.</p>
            </div>
            <!-- Layanan 2 -->
            <div class="col-md-4 text-center">
                <h5>Program Pemberdayaan Keluarga</h5>
                <p>Memberikan edukasi mengenai kesejahteraan keluarga, serta peningkatan kapasitas ekonomi keluarga.</p>
            </div>
            <!-- Layanan 3 -->
            <div class="col-md-4 text-center">
                <h5>Konseling Keluarga</h5>
                <p>Memberikan layanan konseling bagi pasangan suami istri dalam merencanakan keluarga yang sehat.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="content-section">
    <div class="container text-center">
        <h2 class="mb-4">Tim Kami</h2>
        <p class="lead">Kami terdiri dari tim yang berkompeten dan berpengalaman dalam bidang pengendalian penduduk, keluarga berencana, serta kesehatan masyarakat. Berikut adalah beberapa anggota tim kami:</p>
        <div class="row">
            <!-- Team Member 1 -->
            <div class="col-md-4 team-member">
                <img src="assets/img/team1.jpg" alt="Team Member 1">
                <h5>Dr. Andi Saputra</h5>
                <p>Kepala Dinas</p>
            </div>
            <!-- Team Member 2 -->
            <div class="col-md-4 team-member">
                <img src="assets/img/team2.jpg" alt="Team Member 2">
                <h5>Maria Sari</h5>
                <p>Koordinator Program KB</p>
            </div>
            <!-- Team Member 3 -->
            <div class="col-md-4 team-member">
                <img src="assets/img/team3.jpg" alt="Team Member 3">
                <h5>Agus Prabowo</h5>
                <p>Staff Pengendalian Penduduk</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<?php 
include "includes/footer.php"
?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
