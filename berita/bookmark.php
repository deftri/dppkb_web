<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT berita.* FROM bookmark 
        JOIN berita ON bookmark.berita_id = berita.id 
        WHERE bookmark.user_id = '$user_id' 
        ORDER BY bookmark.id DESC";
$result = $conn->query($sql);

echo "<h2>Daftar Bookmark Anda</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='border p-3 mb-3'>";
        echo "<h3><a href='detail_berita.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['judul']) . "</a></h3>";
        echo "<p>" . substr(htmlspecialchars($row['konten']), 0, 100) . "...</p>";
        echo "</div>";
    }
} else {
    echo "<p class='text-muted'>Belum ada berita yang di-bookmark.</p>";
}

$conn->close();
?>
<nav>
    <a href="index.php">Home</a>
    <a href="bookmark.php">Bookmark Saya</a>
    <a href="logout.php">Logout</a>
</nav>
