<?php
// Menyambungkan ke database
include '../config/config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Bersihkan data dari karakter yang tidak diinginkan untuk mencegah SQL Injection
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $subject = mysqli_real_escape_string($conn, $subject);
    $message = mysqli_real_escape_string($conn, $message);

    // Query untuk memasukkan data ke tabel messages_feedback
    $sql = "INSERT INTO messages_feedback (name, email, subject, message, status, created_at)
            VALUES ('$name', '$email', '$subject', '$message', 'pending', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman sukses atau feedback setelah pesan terkirim
        header("Location: feedback-success.php");
        exit();
    } else {
        // Jika terjadi error saat memasukkan data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Menutup koneksi
    $conn->close();
}
?>
