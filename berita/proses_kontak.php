<?php
include '../config/config.php';
$nama = $_POST['nama'];
$email = $_POST['email'];
$pesan = $_POST['pesan'];

$sql = "INSERT INTO kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";
$conn->query($sql);
mail("deftricoca1@gmail.com", "Pesan dari Kontak", "Pesan dari $nama: $pesan");
header("Location: faq.php");
?>
