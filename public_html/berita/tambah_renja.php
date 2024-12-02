<?php
session_start();
include '../config/config.php';
include 'includes/renja-header.php';

// Periksa apakah admin yang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Anda tidak memiliki akses untuk menambah kegiatan.</p>";
    exit;
}

if (isset($_POST['submit'])) {
    $kegiatan = $_POST['kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tahun = $_POST['tahun'];
    $status = $_POST['status'];
    $tanggal_dibuat = date('Y-m-d H:i:s');  // Tanggal dibuat saat ini

    // Query untuk menyimpan data kegiatan ke database
    $sql_insert = "INSERT INTO renja (kegiatan, deskripsi, tahun, status, tanggal_dibuat) 
                   VALUES ('$kegiatan', '$deskripsi', '$tahun', '$status', '$tanggal_dibuat')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<p class='success-msg'>Kegiatan baru berhasil ditambahkan.</p>";
    } else {
        echo "<p class='error-msg'>Error: " . $sql_insert . "<br>" . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="form-container">
        <h3>Tambah Kegiatan Baru</h3>
        <form action="tambah_renja.php" method="POST">
            <div class="form-group">
                <label for="kegiatan">Kegiatan:</label>
                <input type="text" id="kegiatan" name="kegiatan" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="number" id="tahun" name="tahun" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Tertunda">Tertunda</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Tambah Kegiatan</button>
        </form>
    </div>

    <style>
        /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    color: #333;
    line-height: 1.6;
}

/* Form container */
.form-container {
    width: 100%;
    max-width: 600px;
    margin: 30px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h3 {
    text-align: center;
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Form groups */
.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"], input[type="number"], textarea, select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 5px;
}

textarea {
    resize: vertical;
}

/* Button styling */
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

/* Success and Error Messages */
.success-msg {
    color: #28a745;
    text-align: center;
    font-weight: bold;
}

.error-msg {
    color: #dc3545;
    text-align: center;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 20px;
        margin: 10px;
    }
}

    </style>

</body>
</html>
