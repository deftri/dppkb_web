<?php
session_start();
include '../config/config.php';

// Ensure the user is logged in and has a valid session
if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Initialize variables
$user_id = $_SESSION['user_id'];
$session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);
$role = $_SESSION['role'];

// Validate session ownership
$sql_validate_session = "SELECT * FROM chat_sessions WHERE id = ? AND (klien_id = ? OR konselor_id = ? OR psikolog_id = ?)";
$stmt_validate = $conn->prepare($sql_validate_session);
$stmt_validate->bind_param("iiii", $session_id, $user_id, $user_id, $user_id);
$stmt_validate->execute();
$chat_session = $stmt_validate->get_result()->fetch_assoc();

if (!$chat_session) {
    echo "Sesi chat tidak valid atau Anda tidak memiliki akses.";
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

// Fetch chat messages
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/chat_room.css">
    <style>
        /* Custom CSS Styling */

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d4f1f4, #a6c0fe);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container-chat {
            width: 100%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            height: 90vh;
        }
    
        .chat-header {
            background-color: #4caf50; /* Hijau */
            color: white;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        /* Logo Position */
        .chat-header .logo {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: auto;
            border: 3px solid white;
            border-radius: 50%;
            background-color: white;
            padding: 5px;
        }
        .chat-header .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        /* Simplified Dashboard Button */
        .chat-header .buttons .btn-dashboard {
            background-color: #ffffff;
            color: #4caf50;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .chat-header .buttons .btn-dashboard:hover {
            background-color: #388e3c;
            color: white;
        }
        /* Call Button */
        .chat-header .buttons .call-button {
            background-color: #25D366; /* WhatsApp color */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .chat-header .buttons .call-button:hover {
            background-color: #128C7E;
        }
        .chat-header h1 {
            font-size: 1.5rem;
            margin: 0 auto;
            text-align: center;
            width: 100%;
            padding-top: 20px; /* Space for logo */
        }
        .user-info {
            font-size: 1rem;
            text-align: center;
            margin-top: 10px;
            color: #f1f1f1;
        }
        .chat-header button, .chat-header a {
            /* Removed redundant styles */
        }
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #e3f2fd; /* Light blue background */
            scroll-behavior: smooth;
        }
        .message {
            margin: 10px 0;
            padding: 15px;
            border-radius: 10px;
            max-width: 75%;
            display: flex;
            flex-direction: column;
            word-wrap: break-word;
            position: relative;
        }
        .outgoing {
            background: #4caf50;
            color: white;
            margin-left: auto;
            text-align: right;
        }
        .incoming {
            background: #e0f7fa;
            color: #333;
            margin-right: auto;
            text-align: left;
        }
        .sender {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            font-size: 0.9em;
        }
        .timestamp {
            font-size: 0.7em;
            color: #666;
            margin-top: 5px;
            align-self: flex-end;
        }
        .message-input-container {
            padding: 15px;
            display: flex;
            align-items: center;
            background-color: #f1f1f1;
            border-top: 1px solid #ddd;
        }
        .message-input {
            flex: 1;
            padding: 12px 20px;
            border-radius: 25px;
            border: 1px solid #ccc;
            margin-right: 15px;
            font-size: 1rem;
        }
        .button-send {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }
        .button-send:hover {
            background-color: #388e3c;
        }
        .exit-message {
            padding: 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f1f1f1;
            border-top: 1px solid #ddd;
        }
        .exit-button {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9rem;
        }
        .exit-button:hover {
            background-color: #c62828;
        }

        /* Download Button Styling */
        .download-button {
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: #ffffff;
            color: #4caf50;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none;
        }
        .download-button:hover {
            background-color: #388e3c;
            color: white;
        }

        /* Call Button Styling */
        .call-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #25D366; /* WhatsApp color */
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: background-color 0.3s;
        }
        .call-button:hover {
            background-color: #128C7E;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .chat-header h1 {
                font-size: 1.3rem;
            }
            .chat-header .buttons .btn-dashboard,
            .chat-header .buttons .call-button {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
            .user-info {
                font-size: 0.9rem;
            }
            .message {
                font-size: 0.9rem;
            }
            .sender {
                font-size: 0.8em;
            }
            .timestamp {
                font-size: 0.6em;
            }
            .message-input {
                font-size: 0.9rem;
                padding: 10px 15px;
            }
            .button-send {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
            .download-button img {
                width: 10px; /* Slightly larger for better visibility on small screens */
                height: 10px;
            }
        }

        @media (max-width: 480px) {
            .chat-header h1 {
                font-size: 1rem;
            }
            .chat-header .buttons {
                flex-direction: column;
                gap: 5px;
            }
            .chat-header .buttons .btn-dashboard,
            .chat-header .buttons .download-button {
                width: 100%;
                text-align: center;
            }
            .user-info {
                font-size: 0.8rem;
            }
            .message {
                font-size: 0.8rem;
            }
            .sender {
                font-size: 0.7em;
            }
            .timestamp {
                font-size: 0.5em;
            }
            .message-input {
                font-size: 0.8rem;
                padding: 8px 12px;
            }
            .button-send {
                font-size: 0.8rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

<div class="container-chat">
    <div class="chat-header">
        <!-- Buttons Section -->
        <div class="buttons">
            <!-- Simplified Dashboard Return Button -->
            <button class="btn-dashboard" onclick="window.location.href='<?php 
    echo ($role == 'klien') ? '../klien/dashboard-klien.php' : 
          (($role == 'konselor') ? '../konselor/dashboard-konselor.php' : 
          '../psikolog/dashboard-psikolog.php'); ?>'">
    Kembali
</button>




        
            <!-- Download Button -->
            <?php if ($role == 'konselor' || $role == 'psikolog'): ?>
                <a href="<?php echo ($role == 'psikolog') ? '../psikolog/generate_chat_pdf.php' : '../konselor/generate_chat_pdf.php'; ?>?session_id=<?= urlencode($session_id) ?>" class="download-button">
                    <span>Unduh Chat</span>
                    <i class="fas fa-download"></i>
                </a>
            <?php endif; ?>
        </div>
        <h1>Chat Room - Sesi #<?= htmlspecialchars($session_id) ?></h1>
        <span class="user-info"><?= $display_name ?></span>
    </div>

    <!-- Chat Box -->
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

    <!-- Message Input -->
    <div class="message-input-container">
        <input type="text" class="message-input form-control" id="message" placeholder="Tulis pesan..." required onkeypress="if(event.key === 'Enter'){ sendMessage(); event.preventDefault(); }">
        <button class="button-send btn btn-primary" onclick="sendMessage()">Kirim</button>
    </div>

    <!-- Exit Chat Form -->
<?php if ($role === 'konselor' || $role === 'psikolog'): ?>
    <form method="POST" action="" id="exitForm">
        <div class="exit-message">
            <button type="button" onclick="confirmExit()" class="exit-button btn btn-danger">
                Selesaikan Sesi
            </button>
        </div>
        <input type="hidden" name="exit_chat" value="1">
    </form>
<?php endif; ?>



<!-- Fixed Call Button for Klien -->
<?php if ($role === 'klien' && $whatsapp_url): ?>
    <a href="<?= $whatsapp_url ?>" target="_blank" class="call-button">
        <i class="fab fa-whatsapp"></i>
    </a>
<?php endif; ?>

<!-- Font Awesome untuk ikon -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

let isAtBottom = true; // Status apakah pengguna berada di bagian bawah

function checkScrollPosition() {
    const chatBox = document.getElementById('chat-box');
    // Cek apakah pengguna berada di dekat bagian bawah (10px toleransi)
    isAtBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 10;
}

function scrollToBottom() {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight; // Gulir otomatis ke bawah
}

setInterval(function() {
    fetch(`fetch_messages.php?session_id=<?= urlencode($session_id) ?>`)
        .then(response => response.text())
        .then(data => {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = data;
            if (isAtBottom) {
                scrollToBottom(); // Gulir otomatis jika pengguna berada di bawah
            }
        })
        .catch(error => console.error('Error fetching messages:', error));
}, 500); // Polling setiap 500ms

// Event listener untuk melacak posisi scroll pengguna
document.getElementById('chat-box').addEventListener('scroll', checkScrollPosition);

// Autoscroll saat mengirim pesan
function sendMessage() {
    const message = document.getElementById('message').value.trim();
    if (message) {
        fetch(`send_message.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `session_id=<?= urlencode($session_id) ?>&message=${encodeURIComponent(message)}`
        })
        .then(() => {
            document.getElementById('message').value = '';
            scrollToBottom(); // Paksa autoscroll setelah mengirim pesan
        })
        .catch(error => console.error('Error sending message:', error));
    }
}

<?php
// Tandai pesan sebagai sudah dibaca
$sql_mark_as_read = "
    UPDATE chat_messages 
    SET is_read = 1 
    WHERE session_id = ? AND sender_id != ?";
$stmt = $conn->prepare($sql_mark_as_read);
$stmt->bind_param("ii", $session_id, $user_id);
$stmt->execute();
?>


    function confirmExit() {
    if (confirm("Apakah Anda yakin ingin mengakhiri sesi? Chat akan berakhir dan Anda tidak bisa kembali.")) {
        // Submit form jika user mengkonfirmasi
        document.getElementById('exitForm').submit();
    }
}


    document.addEventListener("DOMContentLoaded", function() {
        scrollToBottom();
    });
</script>
</body>
</html>
