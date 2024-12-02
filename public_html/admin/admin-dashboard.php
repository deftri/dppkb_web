<?php
session_start();
include '../config/config.php';
require_once('../admin/vendor/tecnickcom/tcpdf/tcpdf.php');

// Redirect non-admin users to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Logout Handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}

// Fetch users data
function fetchUsers($conn) {
    $sql = "SELECT * FROM users WHERE role = 'klien'";
    return $conn->query($sql);
}

// Fetch messages if session_id is provided
function fetchMessages($conn, $session_id) {
    $sql = "
        SELECT m.message, m.sent_at, u.nama AS sender_nama, u.role AS sender_role
        FROM chat_messages m
        LEFT JOIN users u ON m.sender_id = u.id
        WHERE m.session_id = ? ORDER BY m.sent_at ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Generate PDF
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

// Add new client account
function addClientAccount($conn, $data) {
    $sql = "INSERT INTO users (username, password, nama, nomor_hp, email, role, sub_role) VALUES (?, ?, ?, ?, ?, 'klien', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $data['username'], $data['password'], $data['nama'], $data['nomor_hp'], $data['email'], $data['sub_role']);
    return $stmt->execute();
}

// Delete client account
function deleteClientAccount($conn, $user_id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

$messages = [];
$users_result = fetchUsers($conn);

// Handle message export
if (isset($_GET['export_pdf']) && isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $messages_result = fetchMessages($conn, $session_id);
    if ($messages_result->num_rows > 0) {
        $messages = $messages_result->fetch_all(MYSQLI_ASSOC);
        generatePDF($messages, $session_id);
    } else {
        $_SESSION['error'] = "Tidak ada percakapan untuk diekspor.";
    }
    exit();
}

// Handle adding client account
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_akun'])) {
    $data = [
        'username' => $_POST['username'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'nama' => $_POST['nama'],
        'nomor_hp' => $_POST['nomor_hp'],
        'email' => $_POST['email'],
        'sub_role' => $_POST['sub_role']
    ];

    if (addClientAccount($conn, $data)) {
        $_SESSION['success'] = "Akun berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan akun.";
    }
}

// Handle deleting client account
if (isset($_GET['delete_user_id'])) {
    if (deleteClientAccount($conn, $_GET['delete_user_id'])) {
        $_SESSION['success'] = "Akun berhasil dihapus!";
        // Redirect to admin dashboard after successful deletion
        header("Location: /public_html/admin/admin-dashboard.php");
        exit(); // Ensure that the script stops executing after the redirect
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
        body { font-family: 'Poppins', sans-serif; }
        .message-box { padding: 15px; margin-bottom: 10px; background-color: #f8f9fa; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .message-box .sender { font-weight: bold; }
        .message-box .timestamp { font-size: 0.85rem; color: #6c757d; }
        .btn-custom { background-color: #007bff; color: white; }
        .btn-custom:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container mt-5">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); endif; ?>

    <!-- Card for Pesan Table -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">Daftar Pesan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID Pesan</th>
                        <th>Session ID</th>
                        <th>Pesan Terakhir</th>
                        <th>Waktu Pengiriman</th>
                        <th>Status Baca</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query to get unique messages by session_id and the latest message
                    $query = "
                        SELECT 
                            MAX(id) AS id, 
                            session_id, 
                            message, 
                            MAX(sent_at) AS sent_at, 
                            is_read
                        FROM chat_messages 
                        GROUP BY session_id 
                        ORDER BY sent_at DESC
                    ";

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($message = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($message['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($message['session_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($message['message']) . "</td>";
                            echo "<td>" . htmlspecialchars($message['sent_at']) . "</td>";
                            echo "<td>" . ($message['is_read'] ? 'Baca' : 'Belum Baca') . "</td>";
                            echo "<td>
                                    <a href='generate_chat_pdf.php?session_id=" . $message['session_id'] . "' class='btn btn-info btn-sm'>Download Percakapan</a>
                                    <a href='?hapus_pesan_id=" . $message['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pesan ini?\")'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada pesan untuk ditampilkan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card for Users Table -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">Daftar Pengguna</h5>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <a href="tambah_pengguna.php" class="btn btn-custom" data-bs-toggle="modal">Tambah Akun</a>
            <a href="?logout=true" class="btn btn-danger">Logout</a>
        </div>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Nomor HP</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Sub Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['nama'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['nomor_hp'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['is_online'] ? 'Online' : 'Offline') ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['sub_role'] ?? '') ?></td>
                            <td>
                                <a href="?delete_user_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</a>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Success and Error Messages -->
<div class="container mt-5">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); endif; ?>
</div>



<!-- Riwayat Percakapan -->

<!-- Tombol Ekspor PDF -->
<?php if (isset($_GET['session_id'])): ?>
    <a href="?export_pdf=true&session_id=<?= $_GET['session_id'] ?>" class="btn btn-success mt-3">Ekspor ke PDF</a>
<?php endif; ?>

<style>

    /* Styling untuk message-box */
.message-box {
    padding: 15px;
    margin-bottom: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.message-box .sender {
    font-weight: bold;
    color: #007bff;
}

.message-box .message {
    margin-top: 5px;
    font-size: 1rem;
}

.message-box .timestamp {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 5px;
}

</style>

<script>

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

    // Output file PDF
    $pdf->Output('chat_session_' . $session_id . '.pdf', 'D');
}

</script>