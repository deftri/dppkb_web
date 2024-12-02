<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah konselor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Ambil data psikolog yang online
$sql_psikolog = "
    SELECT id, nama
    FROM users
    WHERE role = 'psikolog' AND is_online = 1;
";
$result = $conn->query($sql_psikolog);
$online_psychologists = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $online_psychologists[] = [
            'id' => $row['id'],
            'name' => $row['nama']
        ];
    }
}

echo json_encode(['status' => 'success', 'psychologists' => $online_psychologists]);
?>
