<?php
include '../    config/config.php';

$komentar_id = $_POST['komentar_id'];
$user_id = $_POST['user_id'];
$alasan = $conn->real_escape_string($_POST['alasan']);

$sql = "INSERT INTO report (komentar_id, user_id, alasan) VALUES ('$komentar_id', '$user_id', '$alasan')";
if ($conn->query($sql) === TRUE) {
    echo "Komentar berhasil dilaporkan!";
} else {
    echo "Error: " . $conn->error;
}

header("Location: detail_berita.php?id=$berita_id");
?>
