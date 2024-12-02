<?php
// Menampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Detail koneksi database
//$host = '103.147.245.13'; // Niagahoster biasanya menggunakan localhost
//$db = 'dppkb_web'; // Nama database Anda
//$user = 'root'; // Username database
//$pass = 'Kominfo@1234'; // Masukkan password yang sudah Anda perbarui

$host = 'localhost';
$db = 'u736419561_sinderela';
$user = 'root';
$pass = '';

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
