<?php
session_start();
include '../config/config.php';

// Pastikan user sudah login dan memiliki role yang diizinkan (misalnya, konselor)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor') {
    echo "Akses ditolak.";
    exit();
}

// Cek apakah `session_id` dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_id'])) {
    $session_id = filter_var($_POST['session_id'], FILTER_SANITIZE_NUMBER_INT);
    $konselor_id = $_SESSION['user_id'];

    // Hapus sesi dari database berdasarkan session_id dan konselor_id
    $sql_delete_session = "DELETE FROM chat_sessions WHERE id = ? AND konselor_id = ?";
    $stmt_delete_session = $conn->prepare($sql_delete_session);
    $stmt_delete_session->bind_param("ii", $session_id, $konselor_id);

    if ($stmt_delete_session->execute() && $stmt_delete_session->affected_rows > 0) {
        echo "Sesi berhasil dihapus.";
    } else {
        echo "Gagal menghapus sesi atau sesi tidak ditemukan.";
    }
} else {
    echo "Permintaan tidak valid.";
}
