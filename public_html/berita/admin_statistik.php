<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

echo "<h2>Statistik Kunjungan</h2>";

// Statistik kunjungan per halaman
$sql = "SELECT halaman, SUM(kunjungan) AS total_kunjungan, MAX(tanggal) AS terakhir_kunjungan
        FROM statistik
        GROUP BY halaman
        ORDER BY total_kunjungan DESC";
$result = $conn->query($sql);

echo "<table class='table'>";
echo "<tr><th>Halaman</th><th>Total Kunjungan</th><th>Terakhir Dikunjungi</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['halaman']) . "</td>";
    echo "<td>" . $row['total_kunjungan'] . "</td>";
    echo "<td>" . $row['terakhir_kunjungan'] . "</td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();
?>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_statistik.php">Statistik Kunjungan</a>
    <a href="logout.php">Logout</a>
</nav>

