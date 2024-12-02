<?php
session_start();
include '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = $_POST['message_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE chat_messages SET is_read = 1 WHERE id = ? AND recipient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $message_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil ditandai sebagai dibaca']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menandai pesan']);
    }
}
?>
