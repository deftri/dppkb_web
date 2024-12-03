<?php
session_start();
include '../config/config.php';

// Periksa apakah admin yang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Anda tidak memiliki akses untuk menghapus kegiatan.</p>";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data kegiatan berdasarkan ID
    $sql_delete = "DELETE FROM renja WHERE id = $id";

    if ($conn->query($sql_delete) === TRUE) {
        echo "<p>Kegiatan berhasil dihapus.</p>";
    } else {
        echo "<p>Terjadi kesalahan: " . $conn->error . "</p>";
    }
}
?>

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

/* Container for Content */
.container {
    width: 90%;
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.table-responsive {
    margin-top: 20px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.table th {
    background-color: #4CAF50;
    color: #fff;
}

.table-striped tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tr:hover {
    background-color: #f1f1f1;
}

.btn {
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-warning {
    background-color: #ffc107;
    color: #fff;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

.btn-danger:hover {
    background-color: #c82333;
}

.action-btn {
    text-align: center;
    margin-bottom: 20px;
}

.no-data {
    color: #777;
    text-align: center;
    font-size: 18px;
}

</style>