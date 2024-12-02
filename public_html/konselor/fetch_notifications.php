<?php
session_start();
include '../config/config.php';

header('Content-Type: application/json');

// Pastikan pengguna sudah login dan valid
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$konselor_id = $_SESSION['user_id'];

// Query untuk mengambil pesan yang belum dibaca
$sql = "
    SELECT cm.id, cm.sender_id, cm.message, cm.sent_at 
    FROM chat_messages cm 
    JOIN chat_sessions cs ON cm.session_id = cs.id 
    WHERE cs.konselor_id = ? 
      AND cm.is_read = 0 
      AND cm.sender_id != ?
    ORDER BY cm.sent_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Kesalahan query: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ii", $konselor_id, $konselor_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode(['status' => 'success', 'notifications' => $notifications]);
?>
