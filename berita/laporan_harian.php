<?php
include '../config/config.php';

// Mengambil statistik kunjungan hari ini
$tanggal = date("Y-m-d");
$sql = "SELECT halaman, kunjungan FROM statistik WHERE tanggal = '$tanggal'";
$result = $conn->query($sql);

$laporan = "Laporan Kunjungan Harian:\n\n";
while ($row = $result->fetch_assoc()) {
    $laporan .= "Halaman: " . $row['halaman'] . " - Kunjungan: " . $row['kunjungan'] . "\n";
}

// Mengirim email
$to = "deftricoca1@gmail.com";
$subject = "Laporan Kunjungan Harian - $tanggal";
$headers = "From: no-reply@example.com";

mail($to, $subject, $laporan, $headers);

$conn->close();
?>
