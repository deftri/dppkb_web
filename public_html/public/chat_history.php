<?php
session_start();
include '../config/config.php'; // Path ke koneksi database Anda

// Pastikan pengguna telah login dan memiliki akses ke sesi
if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);

// Ambil data sesi untuk memastikan sesi valid
$sql_session = "SELECT * FROM chat_sessions WHERE id = ?";
$stmt_session = $conn->prepare($sql_session);
$stmt_session->bind_param("i", $session_id);
$stmt_session->execute();
$session_data = $stmt_session->get_result()->fetch_assoc();

if (!$session_data) {
    echo "Sesi tidak ditemukan.";
    exit();
}

// Ambil pesan chat berdasarkan session_id
$sql_messages = "SELECT sender_id, message, sent_at FROM chat_messages WHERE session_id = ? ORDER BY sent_at ASC";
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $session_id);
$stmt_messages->execute();
$messages_result = $stmt_messages->get_result();

// Ambil username untuk tiap sender_id
$usernames = [];
$sql_usernames = "SELECT id, username FROM users";
$usernames_result = $conn->query($sql_usernames);
while ($row = $usernames_result->fetch_assoc()) {
    $usernames[$row['id']] = $row['username'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }
        .chat-history {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin-bottom: 15px;
        }
        .sender {
            font-weight: bold;
        }
        .timestamp {
            font-size: 0.8em;
            color: #666;
        }
        .message-text {
            margin-top: 5px;
            padding: 10px;
            border-radius: 5px;
        }
        .outgoing {
            background-color: #4a90e2;
            color: white;
            text-align: right;
        }
        .incoming {
            background-color: #e8e8e8;
            color: #333;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="chat-history">
        <h2>Riwayat Chat - Sesi #<?= htmlspecialchars($session_id) ?></h2>

        <?php while ($msg = $messages_result->fetch_assoc()): ?>
            <div class="message <?= $msg['sender_id'] == $user_id ? 'outgoing' : 'incoming' ?>">
                <div class="sender">
                    <?= htmlspecialchars($usernames[$msg['sender_id']] ?? 'Pengguna Tidak Dikenal') ?>
                    <span class="timestamp"><?= htmlspecialchars($msg['sent_at']) ?></span>
                </div>
                <div class="message-text"><?= htmlspecialchars($msg['message']) ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
