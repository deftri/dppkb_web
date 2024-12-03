<?php
// Koneksi ke database
include '../config/config.php';

// Mendapatkan ID gambar dari query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $galeri_id = $_GET['id'];

    // Mengambil informasi gambar berdasarkan ID
    $sql = "SELECT * FROM galeri WHERE id = $galeri_id";
    $result = $conn->query($sql);

    // Jika gambar ditemukan
    if ($result && $result->num_rows > 0) {
        $galeri_item = $result->fetch_assoc();
        $galeri_image_path = htmlspecialchars($galeri_item['file_path']);
        $galeri_caption = htmlspecialchars($galeri_item['caption']);
    } else {
        $error_message = "Gambar tidak ditemukan.";
    }

    // Mendapatkan gambar sebelumnya dan berikutnya
    $previous_sql = "SELECT * FROM galeri WHERE id < $galeri_id ORDER BY id DESC LIMIT 1";
    $previous_result = $conn->query($previous_sql);
    $next_sql = "SELECT * FROM galeri WHERE id > $galeri_id ORDER BY id ASC LIMIT 1";
    $next_result = $conn->query($next_sql);

    $previous_image = $previous_result && $previous_result->num_rows > 0 ? $previous_result->fetch_assoc() : null;
    $next_image = $next_result && $next_result->num_rows > 0 ? $next_result->fetch_assoc() : null;
} else {
    $error_message = "ID gambar tidak valid.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Gambar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .detail-img {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            object-fit: cover;
        }
        .caption {
            font-size: 1.25rem;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .back-btn, .next-prev-btns {
            margin-top: 20px;
        }
        .container {
            max-width: 900px;
        }
        /* Tombol Next/Previous */
        .next-prev-btns .btn {
            font-size: 1rem;
            padding: 10px 20px;
        }
        .gallery-btns {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .gallery-btns a {
            text-decoration: none;
        }
        .gallery-btns .btn {
            padding: 10px 20px;
            font-size: 1.1rem;
        }
        @media (max-width: 768px) {
            .caption {
                font-size: 1rem; /* Mengurangi ukuran caption pada layar kecil */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Detail Gambar</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php else: ?>
            <div class="text-center">
                <img src="../<?php echo $galeri_image_path; ?>" alt="Gambar Detail" class="detail-img">
                <div class="caption mt-3"><?php echo $galeri_caption; ?></div>
            </div>
        <?php endif; ?>

        <div class="gallery-btns">
            <a href="index.php" class="btn btn-primary back-btn">Kembali ke Galeri</a>

            <!-- Tombol Next dan Previous -->
            <div class="next-prev-btns">
                <?php if ($previous_image): ?>
                    <a href="detail_gambar.php?id=<?php echo $previous_image['id']; ?>" class="btn btn-secondary">Previous</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Previous</button>
                <?php endif; ?>

                <?php if ($next_image): ?>
                    <a href="detail_gambar.php?id=<?php echo $next_image['id']; ?>" class="btn btn-secondary">Next</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Next</button>
                <?php endif; ?>
            </div>
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
