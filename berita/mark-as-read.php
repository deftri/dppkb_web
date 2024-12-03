<?php
include '../config/config.php'; // Menyambungkan ke database

// Mengecek apakah ada parameter ID yang dikirimkan melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Feedback tidak ditemukan.");
}

// Mengambil ID dari parameter URL
$id = $_GET['id'];

// Query untuk memperbarui status feedback menjadi "dibaca"
$sql = "UPDATE messages_feedback SET status = 'read' WHERE id = ?";

// Persiapkan statement dan eksekusi
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Mengeksekusi query
if ($stmt->execute()) {
    // Jika sukses, redirect ke halaman feedback admin
    header("Location: feedback-admin.php?status=success");
    exit;
} else {
    // Jika gagal, tampilkan pesan error
    echo "Terjadi kesalahan saat memperbarui status feedback.";
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
