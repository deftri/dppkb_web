<?php
include '../config/config.php';
session_start();

$id_wilayah = $_SESSION['id_wilayah'];
$sql_klien = "SELECT id, username FROM users WHERE role = 'klien' AND is_online = 1 AND id_wilayah = ?";
$stmt_klien = $conn->prepare($sql_klien);
$stmt_klien->bind_param("i", $id_wilayah);
$stmt_klien->execute();
$result_klien = $stmt_klien->get_result();

if ($result_klien->num_rows > 0) {
    echo "<ul>";
    while ($klien = $result_klien->fetch_assoc()) {
        echo "<li><a href='javascript:void(0);' class='client-link' onclick='startCounseling(" . $klien['id'] . ")'>" . htmlspecialchars($klien['username']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p class='no-clients'>Tidak ada klien yang online di wilayah Anda saat ini.</p>";
}
?>
