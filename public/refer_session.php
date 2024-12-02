<?php
session_start();
include '../config/config.php';

// Verifikasi apakah pengguna adalah konselor dan memiliki session_id yang valid
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor' || !isset($_GET['session_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);

// Perbarui status sesi menjadi 'rujukan'
$sql = "UPDATE chat_sessions SET status = 'rujukan' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);

if ($stmt->execute()) {
    // Redirect kembali ke dashboard konselor
    header("Location: ../konselor/dashboard-konselor.php?referral_success=1");
    exit();
} else {
    echo "Gagal merujuk sesi.";
}
?>
