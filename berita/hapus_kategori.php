<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include("../config/config.php");

$id = $_GET['id'];
$sql = "DELETE FROM kategori WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Kategori berhasil dihapus!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header("Location: kelola_kategori.php");
?>
