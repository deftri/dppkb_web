<?php
// Koneksi ke database
include '../config/config.php';

// Fungsi untuk menambah program
if (isset($_POST['tambah_program'])) {
    $judul_program = $_POST['judul_program'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $gambar = $_FILES['gambar']['name'];
    $link_detail = $_POST['link_detail'];

    // Upload gambar
    $target_dir = "images/";
    $target_file = $target_dir . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

    // Query untuk menambahkan program
    $sql = "INSERT INTO program_ppkb (judul_program, deskripsi, tanggal_mulai, tanggal_berakhir, gambar, link_detail) 
            VALUES ('$judul_program', '$deskripsi', '$tanggal_mulai', '$tanggal_berakhir', '$gambar', '$link_detail')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Program berhasil ditambahkan!'); window.location.href='program-edit.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fungsi untuk mengedit program
if (isset($_POST['edit_program'])) {
    $id = $_POST['id'];
    $judul_program = $_POST['judul_program'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $gambar = $_FILES['gambar']['name'];
    $link_detail = $_POST['link_detail'];

    // Jika gambar tidak diubah, tetap menggunakan gambar lama
    if (empty($gambar)) {
        $gambar = $_POST['gambar_lama'];
    } else {
        // Upload gambar
        $target_dir = "images/";
        $target_file = $target_dir . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);
    }

    // Query untuk mengupdate program
    $sql = "UPDATE program_ppkb SET 
            judul_program='$judul_program', 
            deskripsi='$deskripsi', 
            tanggal_mulai='$tanggal_mulai', 
            tanggal_berakhir='$tanggal_berakhir', 
            gambar='$gambar', 
            link_detail='$link_detail' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Program berhasil diperbarui!'); window.location.href='program-edit.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fungsi untuk menghapus program
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Mengambil nama gambar sebelum menghapus untuk menghapus file gambar
    $sql = "SELECT gambar FROM program_ppkb WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];
        // Hapus gambar dari folder
        unlink("images/" . $gambar);
    }

    // Query untuk menghapus program
    $sql = "DELETE FROM program_ppkb WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Program berhasil dihapus!'); window.location.href='program-edit.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Mengambil data program untuk diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM program_ppkb WHERE id='$id'";
    $result = $conn->query($sql);
    $program = $result->fetch_assoc();
}
?>

<?php include 'includes/renja-header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Program Dinas PP KB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manajemen Program Dinas PP KB</h1>

        <!-- Form untuk tambah/edit program -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="program-edit.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul_program">Judul Program</label>
                        <input type="text" class="form-control" id="judul_program" name="judul_program" value="<?php echo isset($program) ? $program['judul_program'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?php echo isset($program) ? $program['deskripsi'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo isset($program) ? $program['tanggal_mulai'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_berakhir">Tanggal Berakhir</label>
                        <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" value="<?php echo isset($program) ? $program['tanggal_berakhir'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar Program</label>
                        <input type="file" class="form-control" id="gambar" name="gambar">
                        <?php if (isset($program)): ?>
                            <img src="images/<?php echo $program['gambar']; ?>" width="100px" alt="Gambar Program">
                            <input type="hidden" name="gambar_lama" value="<?php echo $program['gambar']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="link_detail">Link Detail</label>
                        <input type="url" class="form-control" id="link_detail" name="link_detail" value="<?php echo isset($program) ? $program['link_detail'] : ''; ?>" required>
                    </div>
                    <?php if (isset($program)): ?>
                        <input type="hidden" name="id" value="<?php echo $program['id']; ?>">
                        <button type="submit" name="edit_program" class="btn btn-primary">Perbarui Program</button>
                    <?php else: ?>
                        <button type="submit" name="tambah_program" class="btn btn-success">Tambah Program</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Daftar Program -->
        <h2 class="mt-5">Daftar Program</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM program_ppkb ORDER BY tanggal_mulai DESC";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='col-md-4'>
                    <div class='card'>
                        <img src='./images/{$row['gambar']}' class='card-img-top' alt='Gambar Program'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row['judul_program']}</h5>
                            <p class='card-text'>" . substr($row['deskripsi'], 0, 150) . "...</p>
                            <a href='program-edit.php?edit={$row['id']}' class='btn btn-warning'>Edit</a>
                            <a href='program-edit.php?hapus={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus program ini?\");'>Hapus</a>
                        </div>
                    </div>
                </div>";
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
