<?php
session_start();
include '../config/config.php';

// Pastikan session ID dan user ID tersedia
if (!isset($_GET['session_id']) || !isset($_SESSION['user_id'])) {
    echo "<div class='message'><p>Session tidak ditemukan atau Anda belum login.</p></div>";
    exit();
}

$session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Ambil daftar username untuk ditampilkan di chat (gunakan prepared statement untuk keamanan)
$sql_usernames = "SELECT id, username FROM users";
$usernames_result = $conn->query($sql_usernames);
$usernames = [];
if ($usernames_result) {
    while ($row = $usernames_result->fetch_assoc()) {
        $usernames[$row['id']] = htmlspecialchars($row['username']); // Sanitasi output
    }
}

// Fungsi untuk menambahkan pesan sistem
function addSystemMessage($conn, $session_id, $message) {
    $sender_id = 9999; // ID khusus untuk pesan sistem
    $sql_system_message = "INSERT INTO chat_messages (session_id, sender_id, message, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt_system_message = $conn->prepare($sql_system_message);
    if ($stmt_system_message) {
        $stmt_system_message->bind_param("iis", $session_id, $sender_id, $message);
        $stmt_system_message->execute();
        $stmt_system_message->close();
    }
}

// ** Kirim pesan pertama kali saat konselor atau klien masuk **
if ($user_role === 'klien' && empty($_SESSION['chat'][$session_id]['klien_joined'])) {
    // Pesan saat klien pertama kali masuk
    $join_message = "Klien telah masuk ke room chat. Silakan mulai percakapan.";
    addSystemMessage($conn, $session_id, $join_message);
    $_SESSION['chat'][$session_id]['klien_joined'] = true; // Tandai klien sudah masuk
}

if ($user_role === 'konselor' && empty($_SESSION['chat'][$session_id]['konselor_joined'])) {
    // Pesan saat konselor pertama kali masuk
    $join_message = "Konselor telah masuk ke room chat. Silakan mulai percakapan.";
    addSystemMessage($conn, $session_id, $join_message);
    $_SESSION['chat'][$session_id]['konselor_joined'] = true; // Tandai konselor sudah masuk
}

// Ambil pesan terbaru sejak waktu tertentu
$last_message_time = $_GET['last_message_time'] ?? '1970-01-01 00:00:00';
// Validasi format waktu (YYYY-MM-DD HH:MM:SS)
if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $last_message_time)) {
    $last_message_time = '1970-01-01 00:00:00';
}

$sql = "SELECT sender_id, message, sent_at 
        FROM chat_messages 
        WHERE session_id = ? AND sent_at > ? 
        ORDER BY sent_at ASC 
        LIMIT 50"; // Batasi pesan terbaru yang diambil
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("is", $session_id, $last_message_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($msg = $result->fetch_assoc()) {
            $message_class = ($msg['sender_id'] == $user_id) ? 'message outgoing' : 'message incoming';
            $sender = ($msg['sender_id'] == 9999) 
                ? "Sistem" 
                : ($msg['sender_id'] == $user_id 
                    ? "Anda" 
                    : ($usernames[$msg['sender_id']] ?? "Unknown User"));

            echo "<div class='{$message_class}'>";
            echo "<span class='sender'>" . htmlspecialchars($sender) . ":</span>";
            echo "<span>" . htmlspecialchars($msg['message']) . "</span>";
            echo "<small class='timestamp'>" . htmlspecialchars($msg['sent_at']) . "</small>";
            echo "</div>";
        }
    } else {
        if ($last_message_time === '1970-01-01 00:00:00') {
            echo "<div class='message'><p>Belum ada pesan di sesi ini.</p></div>";
        }
    }
    $stmt->close();
} else {
    echo "<div class='message'><p>Kesalahan dalam mengambil pesan.</p></div>";
    error_log("Query Error: " . $conn->error);
}

// Tambahkan pesan saat pengguna meninggalkan room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_room'])) {
    $leave_message = "{$user_role} telah meninggalkan room.";
    addSystemMessage($conn, $session_id, $leave_message);

    // Mengakhiri sesi dan kembali ke halaman login
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}
?>
