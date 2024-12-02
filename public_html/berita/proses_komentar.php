<?php
include '../config/config.php';
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    echo "Anda harus login sebagai admin untuk melakukan tindakan ini.";
    exit;
}

$berita_id = $_POST['berita_id'];
$nama = $_POST['nama'];
$isi = $_POST['isi'];

// Menambahkan komentar ke database menggunakan prepared statements
$stmt = $conn->prepare("INSERT INTO komentar (berita_id, nama, isi) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $berita_id, $nama, $isi);

if ($stmt->execute()) {
    echo "Komentar berhasil ditambahkan!";

    // Menambahkan notifikasi komentar untuk admin
    $admin_id = $_SESSION['admin']; 
    $isi_notifikasi = "Komentar baru ditambahkan pada berita ID: " . $berita_id;

    // Pastikan kolom 'isi_notifikasi' ada di tabel 'notifikasi'
    $stmt_notif = $conn->prepare("INSERT INTO notifikasi (user_id, isi_notifikasi) VALUES (?, ?)");
    $stmt_notif->bind_param("is", $admin_id, $isi_notifikasi);

    if ($stmt_notif->execute()) {
        echo "Notifikasi berhasil ditambahkan!";
    } else {
        echo "Error Notifikasi: " . $stmt_notif->error;
    }

    $stmt_notif->close();
} else {
    echo "Error Komentar: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: detail_berita.php?id=$berita_id");
exit;
?>
