<?php
session_start();
include '../config/config.php';

// Pastikan pengguna sudah login dan metode adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $session_id = filter_var($_POST['session_id'], FILTER_SANITIZE_NUMBER_INT);
    $sender_id = $_SESSION['user_id'];
    $message = trim(filter_var($_POST['message'], FILTER_SANITIZE_STRING));

    // Validasi input
    if (empty($session_id) || empty($message)) {
        echo "Session ID atau pesan kosong.";
        exit();
    }

    // Simpan pesan ke database
    $sql = "INSERT INTO chat_messages (session_id, sender_id, message, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iis", $session_id, $sender_id, $message);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Pesan berhasil dikirim";
            } else {
                echo "Gagal mengirim pesan, tidak ada baris yang diubah.";
            }
        } else {
            echo "Kesalahan saat mengirim pesan. Silakan coba lagi.";
            error_log("Execute Error: " . $stmt->error); // Log error untuk debugging
        }
    } else {
        echo "Kesalahan dalam menyiapkan statement. Hubungi admin.";
        error_log("Prepare Error: " . $conn->error); // Log error untuk debugging
    }
} else {
    echo "Metode tidak valid atau Anda belum login."; // Pesan ini muncul jika metode bukan POST atau user tidak login
}
?>
