<?php
session_start();
include '../config/config.php';

$klien_id = isset($_GET['klien_id']) ? $_GET['klien_id'] : null;
$konselor_id = $_SESSION['user_id'];
$id_wilayah = $_GET['id_wilayah'];

// Cek apakah sudah ada sesi aktif untuk klien ini di wilayah ini
$sql_check = "SELECT id FROM chat_sessions WHERE klien_id = ? AND id_wilayah = ? AND status = 'berlangsung'";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $klien_id, $id_wilayah);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Jika sudah ada sesi aktif, arahkan ke sesi tersebut
    $active_session = $result_check->fetch_assoc();
    $session_id = $active_session['id'];
} else {
    // Jika tidak ada sesi aktif, buat sesi baru dengan konselor yang sesuai
    $sql_insert = "INSERT INTO chat_sessions (klien_id, konselor_id, id_wilayah, status) VALUES (?, ?, ?, 'berlangsung')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iii", $klien_id, $konselor_id, $id_wilayah);
    $stmt_insert->execute();
    $session_id = $conn->insert_id; // Dapatkan ID sesi baru
}

// Redirect ke halaman chat dengan ID sesi yang baru atau yang sudah ada
header("Location: chat_room.php?session_id=" . $session_id);
exit();
