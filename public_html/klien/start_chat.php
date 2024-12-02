<?php
session_start();
include '../config/config.php';

// Ambil input dari form atau sesi pengguna
$id_wilayah = $_POST['id_wilayah']; // ID wilayah dari input pengguna
$klien_id = $_SESSION['user_id']; // ID klien dari sesi pengguna

// Validasi apakah id_wilayah valid
$sql_validate = "SELECT id FROM wilayah WHERE id = ?";
$stmt_validate = $conn->prepare($sql_validate);
$stmt_validate->bind_param("i", $id_wilayah);
$stmt_validate->execute();
$valid_wilayah = $stmt_validate->get_result()->fetch_assoc();

if (!$valid_wilayah) {
    echo "Wilayah tidak valid.";
    exit();
}

// Cek apakah sudah ada sesi aktif di kecamatan tersebut
$sql = "SELECT * FROM chat_sessions WHERE id_wilayah = ? AND status = 'berlangsung' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_wilayah);
$stmt->execute();
$existing_session = $stmt->get_result()->fetch_assoc();

if ($existing_session) {
    // Jika ada sesi aktif, gunakan sesi ini
    $session_id = $existing_session['id'];
} else {
    // Jika tidak ada sesi aktif, pilih konselor yang online di wilayah yang sama
    $sql_konselor = "SELECT id FROM users WHERE role = 'konselor' AND id_wilayah = ? AND is_online = 1 LIMIT 1";
    $stmt_konselor = $conn->prepare($sql_konselor);
    $stmt_konselor->bind_param("i", $id_wilayah);
    $stmt_konselor->execute();
    $konselor = $stmt_konselor->get_result()->fetch_assoc();

    if ($konselor) {
        // Jika konselor tersedia, buat sesi baru
        $konselor_id = $konselor['id'];
        $sql = "INSERT INTO chat_sessions (klien_id, konselor_id, id_wilayah, status) VALUES (?, ?, ?, 'berlangsung')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $klien_id, $konselor_id, $id_wilayah);
        $stmt->execute();
        $session_id = $conn->insert_id; // Mendapatkan ID sesi baru
    } else {
        echo "Tidak ada konselor yang tersedia di wilayah ini.";
        exit();
    }
}

// Simpan session_id untuk sesi aktif ini
$_SESSION['session_id'] = $session_id;

// Redirect ke halaman chat
header("Location: chat_room.php");
exit();
?>
