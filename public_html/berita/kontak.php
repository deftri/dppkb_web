<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background-color: #28a745;
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
        .contact-form input, .contact-form textarea {
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
        }
        .contact-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1.1rem;
            cursor: pointer;
        }
        .contact-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<?php 
include "includes/header.php"
?>

<!-- Contact Us Section -->
<section class="content-section">
    <div class="container">
        <h2 class="text-center mb-4">Kontak Kami</h2>
        <p class="lead text-center">Jika Anda memiliki pertanyaan atau ingin mendapatkan informasi lebih lanjut, silakan menghubungi kami melalui formulir di bawah ini atau menggunakan informasi kontak yang tersedia.</p>

        <!-- Contact Form -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <form action="send_message.php" method="POST" class="contact-form">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="content-section bg-light">
    <div class="container text-center">
        <h2 class="mb-4">Informasi Kontak</h2>
        <p class="lead">Berikut adalah informasi kontak yang dapat Anda gunakan untuk menghubungi Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim:</p>
        <p><strong>Email:</strong> dp3kb@muaraenim.go.id</p>
        <p><strong>Telepon:</strong> (0731) 123456</p>
        <p><strong>Alamat:</strong> Jl. Raya No. 10, Muara Enim, Sumatera Selatan</p>
        <p><strong>Jam Operasional:</strong> Senin - Jumat, 08:00 - 16:00 WIB</p>
    </div>
</section>

<!-- Footer Section -->
<?php 
include "includes/footer.php"
?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
