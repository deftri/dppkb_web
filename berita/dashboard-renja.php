<?php
session_start();
include '../config/config.php';
include 'includes/renja-header.php';


// Periksa apakah admin yang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Anda tidak memiliki akses untuk mengelola kegiatan.</p>";
    exit;
}

// Ambil data kegiatan dari database
$sql = "SELECT * FROM renja ORDER BY tahun DESC, kegiatan ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kegiatan Renja - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <header>
            <h2>Dashboard Kegiatan Renja</h2>
        </header>

        <div class="action-btn">
            <a href="tambah_renja.php" class="btn btn-primary">Tambah Kegiatan Baru</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kegiatan</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                                <td><?php echo $row['tahun']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <a href="edit_renja.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                    <a href="delete_renja.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-data">Tidak ada kegiatan yang ditemukan.</p>
        <?php endif; ?>
    </div>
    
    <style>

        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7fc;
    color: #333;
}

h2 {
    font-size: 26px;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
}

/* Container for content */
.container {
    width: 80%;
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

header {
    margin-bottom: 30px;
}

/* Button styling */
.action-btn {
    text-align: center;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 16px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    display: inline-block;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-warning {
    background-color: #ffc107;
    color: white;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Table styling */
.table-container {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
    padding: 15px;
    text-align: left;
    border: 1px solid #ddd;
}

.table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

/* No data message */
.no-data {
    color: #777;
    text-align: center;
    font-size: 18px;
    margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 15px;
    }

    .table th,
    .table td {
        padding: 10px;
    }

    .btn {
        font-size: 14px;
        padding: 8px 15px;
    }
}

    </style>

</body>
</html>

