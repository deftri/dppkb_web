<?php
session_start();
include '../config/config.php';

// Pastikan pengguna sudah login dan memiliki ID user yang valid
if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Ambil ID user dan role dari session
$user_id = $_SESSION['user_id'];
$session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);
$role = $_SESSION['role']; // Role pengguna

// Jika bukan admin, tidak bisa mengakses halaman ini
if ($role !== 'admin') {
    echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit();
}


// Fetch refer status to determine role display (KONSELOR or PSIKOLOG)
$refer_status = $chat_session['refer'];
$display_name = '';
$whatsapp_url = ''; // Initialize WhatsApp URL

// For klien: show KONSELOR or PSIKOLOG based on refer status
if ($role === 'klien') {
    if ($refer_status == 0) {
        $counselor_id = $chat_session['konselor_id'];
        $sql_counselor = "SELECT username AS counselor_name, nomor_hp FROM users WHERE id = ?";
        $stmt_counselor = $conn->prepare($sql_counselor);
        $stmt_counselor->bind_param("i", $counselor_id);
        $stmt_counselor->execute();
        $counselor_info = $stmt_counselor->get_result()->fetch_assoc();

        if ($counselor_info) {
            $display_name = "KONSELOR: " . strtoupper(htmlspecialchars($counselor_info['counselor_name']));
            $whatsapp_number = preg_replace('/[^0-9]/', '', $counselor_info['nomor_hp']); // Sanitize nomor_hp number
            $whatsapp_url = "https://wa.me/$whatsapp_number";
        } else {
            $display_name = "KONSELOR: Tidak ditemukan.";
        }
    } else {
        $psychologist_id = $chat_session['psikolog_id'];
        $sql_psychologist = "SELECT username AS psychologist_name FROM users WHERE id = ?";
        $stmt_psychologist = $conn->prepare($sql_psychologist);
        $stmt_psychologist->bind_param("i", $psychologist_id);
        $stmt_psychologist->execute();
        $psychologist_info = $stmt_psychologist->get_result()->fetch_assoc();

        $display_name = $psychologist_info ? "PSIKOLOG: " . strtoupper(htmlspecialchars($psychologist_info['psychologist_name'])) : "PSIKOLOG: Tidak ditemukan.";
    }
}

// For konselor or psikolog: show client name and wilayah
else {
    $client_id = $chat_session['klien_id'];
    $sql_client = "SELECT u.username AS client_name, w.nama_wilayah AS wilayah
                   FROM users u
                   JOIN wilayah w ON u.id_wilayah = w.id
                   WHERE u.id = ?";
    $stmt_client = $conn->prepare($sql_client);
    $stmt_client->bind_param("i", $client_id);
    $stmt_client->execute();
    $client_info = $stmt_client->get_result()->fetch_assoc();

    $display_name = $client_info ? strtoupper(htmlspecialchars($client_info['client_name'])) . " - " . strtoupper(htmlspecialchars($client_info['wilayah'])) : "Klien tidak ditemukan.";
}

// Fetch usernames for displaying names in each message
$sql_usernames = "SELECT id, username, role FROM users";
$usernames_result = $conn->query($sql_usernames);
$usernames = [];
while ($row = $usernames_result->fetch_assoc()) {
    $usernames[$row['id']] = [
        'name' => strtoupper(htmlspecialchars($row['username'])),
        'role' => strtoupper(htmlspecialchars($row['role']))
    ];
}

// Fetch chat messages for this session
$sql_messages = "SELECT * FROM chat_messages WHERE session_id = ? ORDER BY sent_at";
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $session_id);
$stmt_messages->execute();
$messages = $stmt_messages->get_result();

// Handle session exit and end chat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exit_chat'])) {
    if ($role !== 'konselor' && $role !== 'psikolog') {
        echo "Anda tidak memiliki izin untuk menyelesaikan sesi ini.";
        exit();
    }
    // Update status chat menjadi selesai
    $sql_update_status = "UPDATE chat_sessions SET status = 'selesai' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update_status);
    $stmt_update->bind_param("i", $session_id);
    $stmt_update->execute();

    // Redirect ke dashboard
    $redirect_path = ($role === 'konselor') ? '../konselor/dashboard-konselor.php' : '../psikolog/dashboard-psikolog.php';
    header("Location: $redirect_path");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/chat_room.css">
</head>
<body>
    <div class="container-chat">
        <div class="chat-header">
            <div class="buttons">
                <button class="btn-dashboard" onclick="window.location.href='<?php echo ($role == 'klien') ? '../klien/dashboard-klien.php' : (($role == 'konselor') ? '../konselor/dashboard-konselor.php' : '../psikolog/dashboard-psikolog.php'); ?>'">
                    Kembali
                </button>
            </div>
            <h1>Chat Room - Sesi #<?= htmlspecialchars($session_id) ?></h1>
            <span class="user-info"><?= $display_name ?></span>
        </div>

        <div id="chat-box">
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <?php
                    $isOutgoing = $msg['sender_id'] == $user_id;
                    $senderInfo = $usernames[$msg['sender_id']];
                    $senderDisplay = "{$senderInfo['name']} ({$senderInfo['role']})";
                ?>
                <div class="message <?= $isOutgoing ? 'outgoing' : 'incoming' ?>">
                    <span class="sender"><?= $senderDisplay ?>:</span>
                    <span><?= htmlspecialchars($msg['message']) ?></span>
                    <small class="timestamp"><?= htmlspecialchars($msg['sent_at']) ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="message-input-container">
            <input type="text" class="message-input form-control" id="message" placeholder="Tulis pesan..." required onkeypress="if(event.key === 'Enter'){ sendMessage(); event.preventDefault(); }">
            <button class="button-send btn btn-primary" onclick="sendMessage()">Kirim</button>
        </div>
    </div>
</body>
</html>
