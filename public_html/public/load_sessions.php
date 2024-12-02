<?php
session_start();
include '../config/config.php';

$psikolog_id = $_SESSION['user_id'];
$sql_referred_sessions = "SELECT * FROM chat_sessions WHERE status = 'berlangsung' AND refer = 1 AND psikolog_id = ?";
$stmt_referred_sessions = $conn->prepare($sql_referred_sessions);
$stmt_referred_sessions->bind_param("i", $psikolog_id);
$stmt_referred_sessions->execute();
$referred_sessions_result = $stmt_referred_sessions->get_result();

echo "<table>
        <tr>
            <th>ID Sesi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>";
while ($session = $referred_sessions_result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($session['id']) . "</td>
            <td>" . htmlspecialchars($session['status']) . "</td>
            <td><a href='../public/chat_room.php?session_id=" . $session['id'] . "' class='start-session'>Lanjutkan</a></td>
          </tr>";
}
echo "</table>";
