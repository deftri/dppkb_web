<?php
session_start();
include '../config/config.php'; // Sesuaikan dengan path config Anda

// Pastikan pengguna sudah login dan ada `user_id` di session
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login untuk menggunakan fitur bookmark.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Validasi `berita_id` dari form
if (isset($_POST['berita_id']) && is_numeric($_POST['berita_id'])) {
    $berita_id = (int)$_POST['berita_id'];
    $action = $_POST['action']; // Ambil action (bookmark atau unbookmark)

    // Proses bookmark
    if ($action == 'bookmark') {
        // Cek apakah bookmark sudah ada
        $stmt = $conn->prepare("SELECT * FROM bookmarks WHERE user_id = ? AND berita_id = ?");
        $stmt->bind_param("ii", $user_id, $berita_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Jika belum ada, tambahkan bookmark
            $stmt_insert = $conn->prepare("INSERT INTO bookmarks (user_id, berita_id) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $user_id, $berita_id);
            $stmt_insert->execute();
            $stmt_insert->close();

            $_SESSION['bookmark_message'] = "Berita berhasil ditambahkan ke bookmark!";
        } else {
            $_SESSION['bookmark_message'] = "Berita sudah ada di bookmark Anda.";
        }
        $stmt->close();
    }
    // Proses unbookmark
    elseif ($action == 'unbookmark') {
        $stmt_delete = $conn->prepare("DELETE FROM bookmarks WHERE user_id = ? AND berita_id = ?");
        $stmt_delete->bind_param("ii", $user_id, $berita_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        $_SESSION['bookmark_message'] = "Bookmark berhasil dihapus!";
    }
} else {
    $_SESSION['bookmark_message'] = "ID berita tidak valid.";
}

// Redirect kembali ke halaman detail berita
header("Location: detail_berita.php?id=$berita_id");
exit();
?>
