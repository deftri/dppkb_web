<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_id'])) {
    $session_id = filter_var($_POST['session_id'], FILTER_SANITIZE_NUMBER_INT);

    // Update session status to 'ended'
    $sql_update_status = "UPDATE chat_sessions SET status = 'selesai' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update_status);
    $stmt_update->bind_param("i", $session_id);
    $stmt_update->execute();

    header("Location: ../public/chat_room.php?session_id=" . $session_id);
    exit();
}
?>
