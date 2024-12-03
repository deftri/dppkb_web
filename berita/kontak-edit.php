<?php
// kontak-edit.php
include '../config/config.php'; // Menyambungkan ke database

// Ambil data footer yang ada
$sql = "SELECT * FROM footer WHERE id = 1";
$result = $conn->query($sql);
$footer = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan update ke database
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $fax = $_POST['fax'];

    // Update data di database
    $update_sql = "UPDATE footer SET alamat = ?, email = ?, telepon = ?, fax = ? WHERE id = 1";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $alamat, $email, $telepon, $fax);
    $stmt->execute();

    // Redirect ke halaman admin setelah update
    header('Location: kontak-edit.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontak - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Kontak</h2>
        
        <form method="POST" action="kontak-edit.php">
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo $footer['alamat']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $footer['email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon:</label>
                <input type="text" name="telepon" id="telepon" class="form-control" value="<?php echo $footer['telepon']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="fax" class="form-label">Fax (optional):</label>
                <input type="text" name="fax" id="fax" class="form-control" value="<?php echo $footer['fax']; ?>">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- JS Bootstrap dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
