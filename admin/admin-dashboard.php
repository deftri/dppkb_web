<?php
session_start();
include '../config/config.php';
require_once('../admin/vendor/tecnickcom/tcpdf/tcpdf.php'); // Pastikan TCPDF berada di folder yang benar

// Pastikan pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}

// Ambil data pengguna dengan role 'klien'
$sql_users = "SELECT * FROM users WHERE role = 'klien'";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->execute();
$users_result = $stmt_users->get_result();

// Ambil session percakapan berdasarkan user_id (klien) jika ada
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
        $messages[] = $row;
    }
}

// Handle PDF Export
if (isset($_GET['export_pdf']) && $session_id) {
    if (!empty($messages)) {
        generatePDF($messages, $session_id);
    } else {
        $_SESSION['error'] = "Tidak ada percakapan untuk diekspor.";
    }
    exit();
}

// Fungsi untuk generate PDF
function generatePDF($messages, $session_id) {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Riwayat Percakapan - Session ' . $session_id, 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 12);

    foreach ($messages as $message) {
        $sender = $message['sender_role'] == 'klien' ? 'Klien' : 'Konselor';
        $pdf->MultiCell(0, 10, $sender . ': ' . $message['sender_nama'], 0, 'L');
        $pdf->MultiCell(0, 10, $message['message'], 0, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, 'Sent at: ' . date("d-m-Y H:i", strtotime($message['sent_at'])), 0, 'L');
        $pdf->Ln(3);
    }

    $pdf->Output('chat_session_' . $session_id . '.pdf', 'D');
}

// Ambil session percakapan yang pernah dilakukan oleh pengguna
$sessions = [];
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    // Query hanya mengambil sesi berdasarkan sender_id karena receiver_id tidak ada
    $sql_sessions = "
        SELECT DISTINCT session_id 
        FROM chat_messages 
        WHERE sender_id = ? 
        ORDER BY session_id DESC
    ";
    $stmt_sessions = $conn->prepare($sql_sessions);
    $stmt_sessions->bind_param("i", $user_id);
    $stmt_sessions->execute();
    $sessions_result = $stmt_sessions->get_result();

    while ($row = $sessions_result->fetch_assoc()) {
        $sessions[] = $row['session_id'];
    }
}

// Tambah Akun Klien
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_akun'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, nama, nomor_hp, email, role) VALUES (?, ?, ?, ?, ?, 'klien')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $password, $nama, $nomor_hp, $email);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Akun berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan akun.";
    }
}

// Hapus Akun Klien
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_user_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Akun berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus akun.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .message-box {
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message-box .sender {
            font-weight: bold;
        }
        .message-box .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Success/Error Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
        </div>
    <?php unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
        </div>
    <?php unset($_SESSION['error']); endif; ?>

    <h2 class="mb-4">Daftar Pengguna (Klien)</h2>

    <div class="mb-4">
        <a href="#tambahAkunModal" class="btn btn-custom" data-bs-toggle="modal">Tambah Akun</a>
        <a href="?logout=true" class="btn btn-danger">Logout</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Nomor HP</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['nama']) ?></td>
                        <td><?= htmlspecialchars($user['nomor_hp']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['is_online'] ? 'Online' : 'Offline') ?></td>
                        <td>
                          <a href="view_chat.php?user_id=<?= $user['id'] ?>" class="btn btn-info btn-sm">Lihat Chat</a>
                            <a href="?delete_user_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</a>
                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Session Percakapan -->
    <?php if (isset($sessions) && count($sessions) > 0): ?>
        <h3 class="mt-5">Daftar Percakapan</h3>
        <ul class="list-group">
            <?php foreach ($sessions as $session_id): ?>
                <li class="list-group-item">
                    <a href="?session_id=<?= $session_id ?>" class="btn btn-link">Session ID: <?= $session_id ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada percakapan untuk klien ini.</p>
    <?php endif; ?>

    <h3 class="mt-5">Riwayat Percakapan</h3>
    <?php if ($session_id): ?>
        <h4>Session ID: <?= htmlspecialchars($session_id) ?></h4>
        <div>
            <?php foreach ($messages as $message): ?>
                <div class="message-box">
                    <div class="sender"><?= htmlspecialchars($message['sender_nama']) ?> (<?= $message['sender_role'] ?>)</div>
                    <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    <div class="timestamp"><?= date("d-m-Y H:i", strtotime($message['sent_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="?session_id=<?= $session_id ?>&export_pdf=true" class="btn btn-success mt-3">Download Percakapan sebagai PDF</a>
    <?php else: ?>
        <p>Tidak ada percakapan untuk sesi ini.</p>
    <?php endif; ?>
</div>

<!-- Modal Tambah Akun -->
<div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAkunModalLabel">Tambah Akun Klien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formTambahAkun">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_hp" class="form-label">Nomor HP</label>
                        <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" name="tambah_akun" class="btn btn-primary">Tambah Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('formTambahAkun');
        form.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const nama = document.getElementById('nama').value;
            const nomor_hp = document.getElementById('nomor_hp').value;
            const email = document.getElementById('email').value;

            if (!username || !password || !nama || !nomor_hp || !email) {
                e.preventDefault();
                alert('Semua kolom harus diisi!');
                return false;
            }

            // Validasi email
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }

            // Validasi nomor HP
            const phoneRegex = /^[0-9]{10,15}$/;
            if (!phoneRegex.test(nomor_hp)) {
                e.preventDefault();
                alert('Nomor HP harus terdiri dari 10-15 digit!');
                return false;
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
