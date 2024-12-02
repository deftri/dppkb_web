<?php
include '../config/config.php';
session_start();

$konselor_id = $_SESSION['user_id'];
// Tambahkan kondisi refer = 0 untuk mengambil hanya sesi yang belum dirujuk
$sql_sesi_berlangsung = "SELECT id, status FROM chat_sessions WHERE konselor_id = ? AND status IN ('menunggu', 'berlangsung') AND refer = 0";
$stmt_sesi_berlangsung = $conn->prepare($sql_sesi_berlangsung);
$stmt_sesi_berlangsung->bind_param("i", $konselor_id);
$stmt_sesi_berlangsung->execute();
$sesi_berlangsung_result = $stmt_sesi_berlangsung->get_result();

if ($sesi_berlangsung_result->num_rows > 0) {
    while ($session = $sesi_berlangsung_result->fetch_assoc()) {
        echo "<div class='session-item'>
                <p><span class='session-id'>SESI " . htmlspecialchars($session['id']) . "</span> - Status: " . htmlspecialchars($session['status']) . "</p>
                <a href='../public/chat_room.php?session_id=" . $session['id'] . "' class='button'>Lanjutkan Sesi</a>
              </div>";
    }
} else {
    echo "<p class='no-sessions'>Tidak ada sesi yang sedang berlangsung atau menunggu.</p>";
}
?>
