<?php
session_start();
include '../config/config.php';
include 'includes/renja-header.php';

// Periksa apakah admin yang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Anda tidak memiliki akses untuk mengedit kegiatan.</p>";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kegiatan berdasarkan ID
    $sql = "SELECT * FROM renja WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Data kegiatan tidak ditemukan.</p>";
        exit;
    }
}

if (isset($_POST['submit'])) {
    $kegiatan = $_POST['kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tahun = $_POST['tahun'];
    $status = $_POST['status'];

    // Update data kegiatan
    $sql_update = "UPDATE renja SET kegiatan = '$kegiatan', deskripsi = '$deskripsi', tahun = '$tahun', status = '$status' WHERE id = $id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p class='success-msg'>Kegiatan berhasil diperbarui.</p>";
    } else {
        echo "<p class='error-msg'>Terjadi kesalahan: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan - Renja</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 8px;
            background-color: #f9f9f9;
        }

        textarea {
            resize: vertical;
        }

        button[type="submit"] {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success-msg {
            color: #28a745;
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .error-msg {
            color: #dc3545;
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Button Styles */
        .btn {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Form container width */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group select {
            height: 40px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            button[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Kegiatan</h2>

        <form action="edit_renja.php?id=<?php echo $row['id']; ?>" method="POST">
            <div class="form-group">
                <label for="kegiatan">Kegiatan:</label>
                <input type="text" id="kegiatan" name="kegiatan" value="<?php echo $row['kegiatan']; ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?php echo $row['deskripsi']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="number" id="tahun" name="tahun" value="<?php echo $row['tahun']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Aktif" <?php echo ($row['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Selesai" <?php echo ($row['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                    <option value="Tertunda" <?php echo ($row['status'] == 'Tertunda') ? 'selected' : ''; ?>>Tertunda</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <?php if (isset($message)) echo $message; ?>
    </div>

</body>
</html>
