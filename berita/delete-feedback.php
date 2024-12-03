<?php
include '../config/config.php'; // Menyambungkan ke database

// Mengecek apakah ada parameter ID yang dikirimkan melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Feedback tidak ditemukan.");
}

// Mengambil ID dari parameter URL
$id = $_GET['id'];

// Query untuk menghapus feedback dari database
$sql = "DELETE FROM messages_feedback WHERE id = ?";

// Persiapkan statement dan eksekusi
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Mengeksekusi query
if ($stmt->execute()) {
    // Jika sukses, redirect ke halaman feedback admin dengan status sukses
    header("Location: feedback-admin.php?status=deleted");
    exit;
} else {
    // Jika gagal, tampilkan pesan error
    echo "Terjadi kesalahan saat menghapus feedback.";
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
