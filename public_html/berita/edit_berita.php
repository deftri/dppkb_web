<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../config/config.php';

$id = $_GET['id'];
$sql = "SELECT * FROM berita WHERE id=$id";
$result = $conn->query($sql);
$berita = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    $kategori = $_POST['kategori'];

    // Update gambar jika ada file baru
    if ($_FILES["gambar"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
        $gambar = $target_file;
    } else {
        $gambar = $berita['gambar'];
    }

    $sql = "UPDATE berita SET judul='$judul', konten='$konten', kategori='$kategori', gambar='$gambar' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Berita berhasil diupdate! <a href='admin_dashboard.php' class='btn btn-primary btn-sm'>Kembali ke Dashboard</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2; /* Latar belakang abu-abu terang */
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 800px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            margin-bottom: 30px;
            color: #4e5d6c; /* Warna biru pastel yang lembut */
        }
        .btn-primary {
            background-color: #4e5d6c;
            border-color: #4e5d6c;
        }
        .btn-primary:hover {
            background-color: #3b4753;
            border-color: #2f3a44;
        }
        .form-label {
            font-weight: 600;
            color: #4e5d6c;
        }
        .alert {
            margin-top: 20px;
        }
        .form-control {
            border-radius: 5px;
            border-color: #ced4da;
        }
        .form-control:focus {
            border-color: #4e5d6c; /* Fokus border lebih gelap */
        }
        .img-fluid {
            max-width: 150px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Edit Berita</h2>
    
    <!-- Form Edit Berita -->
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" name="judul" id="judul" class="form-control" value="<?php echo htmlspecialchars($berita['judul']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="konten" class="form-label">Konten</label>
            <textarea name="konten" id="konten" class="form-control" rows="6" required><?php echo htmlspecialchars($berita['konten']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo htmlspecialchars($berita['kategori']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar (opsional)</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>

        <div class="mb-3">
            <img src="<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Gambar Berita" class="img-fluid">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Berita</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
