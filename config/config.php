<?php
// Menampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Detail koneksi database
$host = 'localhost'; // Niagahoster biasanya menggunakan localhost
$db = 'u736419561_sinderela'; // Nama database Anda
$user = 'root'; // Username database
$pass = ''; // Masukkan password yang sudah Anda perbarui

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
