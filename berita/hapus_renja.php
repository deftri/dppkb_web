<?php
include '../config/config.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query untuk menghapus kegiatan berdasarkan ID
$query = "DELETE FROM renja WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

// Cek apakah berhasil menghapus
if ($stmt->affected_rows > 0) {
    echo "<p>Data berhasil dihapus.</p>";
} else {
    echo "<p>Gagal menghapus data.</p>";
}

$stmt->close();
?>

<a href="renja.php">Kembali ke Daftar Kegiatan</a>

<?php
// Menutup koneksi database
$conn->close();
?>
