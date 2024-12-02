<?php
session_start();
include '../config/config.php';

if (isset($_POST['rating']) && isset($_POST['berita_id'])) {
    $berita_id = $_POST['berita_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO rating (berita_id, user_id, rating) VALUES ('$berita_id', '$user_id', '$rating')";
    if ($conn->query($sql) === TRUE) {
        echo "Terima kasih atas penilaian Anda!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header("Location: detail_berita.php?id=$berita_id");
}
?>
