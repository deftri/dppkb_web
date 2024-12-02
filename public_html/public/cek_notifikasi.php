<?php
session_start();
include '../config/config.php';

$psikolog_id = $_SESSION['user_id'];
$sql_check_notification = "SELECT id FROM chat_sessions WHERE psikolog_id = ? AND notifikasi = 1 LIMIT 1";
$stmt_check = $conn->prepare($sql_check_notification);
$stmt_check->bind_param("i", $psikolog_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    $sql_reset_notification = "UPDATE chat_sessions SET notifikasi = 0 WHERE psikolog_id = ? AND notifikasi = 1";
    $stmt_reset = $conn->prepare($sql_reset_notification);
    $stmt_reset->bind_param("i", $psikolog_id);
    $stmt_reset->execute();

    echo json_encode(['notifikasi' => true]);
} else {
    echo json_encode(['notifikasi' => false]);
}
