<?php
// Koneksi ke database
include '../config/config.php';

// Mengambil data program dari database
$sql = "SELECT * FROM program_ppkb ORDER BY tanggal_mulai DESC";
$result = $conn->query($sql);

include 'includes/header.php'; // Menyertakan header, jika ada
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Dinas PP KB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .program-card {
            margin-bottom: 1.5rem;
        }

        .program-card img {
            height: 200px;
            object-fit: cover;
        }

        .program-card-body {
            text-align: center;
            padding: 20px;
        }

        .program-card-body .card-title {
            font-size: 1.25rem; /* Judul lebih besar */
            font-weight: bold;
            color: #007bff;
        }

        .program-card-body .card-text {
            font-size: 1rem; /* Deskripsi ukuran sedang */
            color: #555;
            margin-bottom: 10px;
            height: 70px; /* Membatasi tinggi deskripsi */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .program-card-body p {
            font-size: 0.85rem; /* Tanggal lebih kecil */
            color: #6c757d;
            margin-bottom: 10px;
        }

        .btn-primary {
            width: 100%; /* Tombol lebar penuh */
            padding: 10px;
            font-size: 1rem;
            background-color: #007bff;
            border-color: #007bff;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05); /* Efek hover untuk card */
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            .program-card img {
                height: 150px; /* Gambar lebih kecil di perangkat kecil */
            }

            .program-card-body .card-title {
                font-size: 1.15rem; /* Judul sedikit lebih kecil */
            }

            .program-card-body .card-text {
                font-size: 0.95rem; /* Deskripsi sedikit lebih kecil */
            }

            .btn-primary {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Program Dinas PP KB</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='col-md-4 program-card'>
                        <div class='card'>
                            <img src='images/{$row['gambar']}' class='card-img-top' alt='Gambar Program'>
                            <div class='card-body program-card-body'>
                                <h5 class='card-title'>{$row['judul_program']}</h5>
                                <p class='card-text'>" . substr($row['deskripsi'], 0, 150) . "...</p>
                                <p><strong>Mulai: </strong>" . date("d F Y", strtotime($row['tanggal_mulai'])) . "</p>
                                <a href='{$row['link_detail']}' class='btn btn-primary' target='_blank'>Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p class='col-12 text-center'>Tidak ada program yang tersedia.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
