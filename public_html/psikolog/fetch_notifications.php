<?php
session_start();
include '../config/config.php';

// Pastikan pengguna sudah login dan memiliki peran psikolog
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psikolog') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$psikolog_id = $_SESSION['user_id'];

// Hitung pesan baru untuk sesi aktif psikolog
$sql_new_messages = "
    SELECT COUNT(*) AS new_messages
    FROM chat_messages cm
    JOIN chat_sessions cs ON cm.session_id = cs.id
    WHERE cs.psikolog_id = ? 
      AND cs.status = 'berlangsung' 
      AND cm.is_read = 0 
      AND cm.sender_id != ?";
$stmt = $conn->prepare($sql_new_messages);
$stmt->bind_param("ii", $psikolog_id, $psikolog_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['status' => 'success', 'new_messages' => $result['new_messages']]);
?>
