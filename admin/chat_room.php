<?php
session_start();
include '../config/config.php';

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Ambil daftar sesi chat yang sudah selesai atau sedang berlangsung
$sql_sessions = "
    SELECT 
        cs.id AS session_id, 
        cs.klien_id, 
        cs.konselor_id, 
        cs.status, 
        u_klien.nama AS klien_nama, 
        u_konselor.nama AS konselor_nama,
        w.nama_wilayah
    FROM 
        chat_sessions cs
    LEFT JOIN 
        users u_klien ON cs.klien_id = u_klien.id
    LEFT JOIN 
        users u_konselor ON cs.konselor_id = u_konselor.id
    LEFT JOIN 
        wilayah w ON cs.id_wilayah = w.id
    ORDER BY 
        cs.id DESC
";
$stmt_sessions = $conn->prepare($sql_sessions);
$stmt_sessions->execute();
$sessions_result = $stmt_sessions->get_result();

// Jika admin memilih sesi tertentu, ambil pesan-pesan dalam sesi tersebut
$selected_session = null;
$messages = [];

if (isset($_GET['session_id'])) {
    $session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Verifikasi bahwa sesi tersebut ada
    $sql_verify = "SELECT * FROM chat_sessions WHERE id = ?";
    $stmt_verify = $conn->prepare($sql_verify);
    $stmt_verify->bind_param("i", $session_id);
    $stmt_verify->execute();
    $verify_result = $stmt_verify->get_result();
    
    if ($verify_result->num_rows > 0) {
        $selected_session = $verify_result->fetch_assoc();
        
        // Ambil pesan-pesan dalam sesi tersebut
        $sql_messages = "
            SELECT 
                m.message, 
                m.timestamp, 
                u.nama AS sender_nama, 
                u.role AS sender_role
            FROM 
                chat_messages m
            LEFT JOIN 
                users u ON m.sender_id = u.id
            WHERE 
                m.session_id = ?
            ORDER BY 
                m.timestamp ASC
        ";
        $stmt_messages = $conn->prepare($sql_messages);
        $stmt_messages->bind_param("i", $session_id);
        $stmt_messages->execute();
        $messages_result = $stmt_messages->get_result();
        
        while ($row = $messages_result->fetch_assoc()) {
            $messages[] = $row;
        }
        
        // Update status sesi menjadi 'selesai' jika sesi sebelumnya 'berlangsung'
        if ($selected_session['status'] === 'berlangsung') {
            $update_status = "UPDATE chat_sessions SET status = 'selesai' WHERE id = ?";
            $stmt_update = $conn->prepare($update_status);
            $stmt_update->bind_param("i", $session_id);
            $stmt_update->execute();
            $selected_session['status'] = 'selesai';
        }
    } else {
        // Jika sesi tidak ditemukan, redirect kembali ke dashboard tanpa session_id
        header("Location: admin-dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sinderela</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 18px;
        }

        .navbar-custom a {
            color: white;
            text-decoration: none;
        }

        .navbar-custom a:hover {
            color: #ffcc00;
        }

        /* Container for Dashboard */
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        /* Styling for List Group */
        .list-group-item {
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #007bff;
            color: white;
        }

        /* Chat Room Styling */
        .chat-room {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .chat-history {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .chat-message {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
        }

        .chat-message.klien {
            justify-content: flex-end;
        }

        .chat-message.konselor {
            justify-content: flex-start;
        }

        .chat-bubble {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            position: relative;
        }

        .chat-bubble.klien {
            background-color: #e0f7fa;
            text-align: right;
        }

        .chat-bubble.konselor {
            background-color: #ffeb3b;
            text-align: left;
        }

        .chat-message small {
            font-size: 12px;
            color: #999;
            display: block;
            margin-top: 5px;
        }

        .session-header {
            font-size: 18px;
            margin-bottom: 10px;
            color: #555;
        }

        .status {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: green;
        }

        .back-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }

        .back-btn:hover {
            background-color: #218838;
        }

        /* Scrollbar Styling for Chat History */
        .chat-history::-webkit-scrollbar {
            width: 8px;
        }

        .chat-history::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .chat-history::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .chat-history::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a href="#" class="navbar-brand">Sinderela Admin</a>
    </nav>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <h2>Dashboard Admin</h2>
        
        <!-- Daftar Sesi Chat -->
        <div class="mt-4">
            <h4>Daftar Sesi Chat</h4>
            <div class="list-group">
                <?php while ($session = $sessions_result->fetch_assoc()): ?>
                    <a href="admin-dashboard.php?session_id=<?= htmlspecialchars($session['session_id']) ?>" class="list-group-item">
                        <strong>Sesi #<?= htmlspecialchars($session['session_id']) ?> - </strong>
                        Klien: <?= htmlspecialchars($session['klien_nama']) ?>, Konselor: <?= htmlspecialchars($session['konselor_nama']) ?>
                        <br>
                        Wilayah: <?= htmlspecialchars($session['nama_wilayah']) ?> | Status: <?= htmlspecialchars($session['status']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Jika session_id dipilih, tampilkan pesan -->
        <?php if ($selected_session): ?>
            <div class="chat-room">
                <h4>Riwayat Chat Room #<?= htmlspecialchars($session_id) ?></h4>
                <div class="session-header">Percakapan antara Klien dan Konselor/Psikolog</div>
                <div class="chat-history">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <?php
                                // Tentukan pengirim: klien atau konselor/psikolog
                                if ($message['sender_role'] === 'klien') {
                                    $message_class = 'klien';
                                } else {
                                    $message_class = 'konselor';
                                }
                            ?>
                            <div class="chat-message <?= $message_class ?>">
                                <div class="chat-bubble <?= $message_class ?>">
                                    <strong><?= htmlspecialchars($message['sender_nama']) ?>:</strong>
                                    <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                    <small><?= date("d-m-Y H:i", strtotime($message['timestamp'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada percakapan dalam sesi ini.</p>
                    <?php endif; ?>
                </div>
                <a href="admin-dashboard.php" class="back-btn">Kembali ke Daftar Sesi</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
