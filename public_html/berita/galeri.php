<?php
// Koneksi ke database
include '../config/config.php';

// Ambil data gambar dari tabel galeri
$sql = "SELECT * FROM galeri ORDER BY upload_date DESC"; // Mengambil semua gambar dari tabel 'galeri'
$result = $conn->query($sql);
?>

<?php include 'includes/header.php' ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        /* Gaya untuk gambar dalam galeri */
        .gallery img {
            width: 100%; /* Gambar akan mengisi seluruh lebar kolom */
            height: 250px; /* Tentukan tinggi gambar secara tetap */
            object-fit: cover; /* Memastikan gambar terjaga proporsinya */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Gaya untuk setiap card gambar */
        .gallery .col-md-4 {
            margin-bottom: 20px;
        }

        /* Styling untuk card */
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .card-body {
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        /* Styling pagination */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        /* Styling untuk container galeri */
        .gallery-container {
            padding: 30px 0;
        }

        /* Responsif untuk perangkat dengan lebar layar kecil */
        @media (max-width: 768px) {
            .gallery .col-md-4 {
                flex: 1 1 100%; /* Gambar menjadi satu kolom pada layar kecil */
            }

            .gallery img {
                height: 200px; /* Mengurangi tinggi gambar di perangkat kecil */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 gallery-container">
        <h1 class="text-center mb-4">Galeri Foto</h1>

        <div class="gallery row">
            <?php
            // Cek jika ada gambar di database
            if ($result->num_rows > 0) {
                // Output data setiap baris
                while($row = $result->fetch_assoc()) {
                    // Ambil informasi gambar
                    $gambar_url = "" . $row['file_path'];  // Tambahkan folder 'uploads/galeri' sebelum nama file gambar
                    $caption = $row['caption'];
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="../<?php echo $gambar_url; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($caption); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($caption); ?></h5>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>Tidak ada gambar yang tersedia.</p>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
