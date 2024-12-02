<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Ambil session_id dari URL
$session_id = isset($_GET['session_id']) ? filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT) : null;
$messages = [];

if ($session_id) {
    // Ambil data percakapan berdasarkan session_id
    $sql_messages = "
        SELECT m.message, m.sent_at, u.nama AS sender_nama, u.role AS sender_role
        FROM chat_messages m
        LEFT JOIN users u ON m.sender_id = u.id
        WHERE m.session_id = ?
        ORDER BY m.sent_at ASC
    ";
    $stmt_messages = $conn->prepare($sql_messages);
    $stmt_messages->bind_param("i", $session_id);
    $stmt_messages->execute();
    $messages_result = $stmt_messages->get_result();
    
    while ($row = $messages_result->fetch_assoc()) {
        // Pastikan setiap nilai tidak null dengan menggunakan null coalescing operator (??)
        $messages[] = [
            'message' => $row['message'] ?? 'Pesan tidak tersedia',
            'sent_at' => $row['sent_at'],
            'sender_nama' => $row['sender_nama'] ?? 'Nama tidak diketahui',
            'sender_role' => $row['sender_role'] ?? 'Role tidak diketahui'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Percakapan - Session <?= htmlspecialchars($session_id ?? 'Tidak Diketahui') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .message-box {
            padding: 20px;
            margin-bottom: 15px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }

        .message-box:hover {
            box-shadow: 0 6px 8px rgba(0,0,0,0.2);
        }

        .sender {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        .message-content {
            font-size: 1rem;
            color: #555;
            margin-top: 10px;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #aaa;
            margin-top: 10px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 20px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .container {
            max-width: 900px;
            margin-top: 50px;
        }

        h2 {
            font-size: 1.75rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Riwayat Percakapan -->
    <h2>Riwayat Percakapan - Session ID: <?= htmlspecialchars($session_id ?? 'Tidak Diketahui') ?></h2>

    <div>
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-box">
                    <div class="sender"><?= htmlspecialchars($message['sender_nama']) ?> (<?= ucfirst(htmlspecialchars($message['sender_role'])) ?>)</div>
                    <div class="message-content"><?= nl2br(htmlspecialchars($message['message'])) ?></div>
                    <div class="timestamp"><?= date("d-m-Y H:i", strtotime($message['sent_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada percakapan untuk sesi ini.</p>
        <?php endif; ?>
    </div>

    <!-- Kembali ke Dashboard -->
    <a href="admin-dashboard.php" class="btn btn-back mt-3">Kembali ke Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
