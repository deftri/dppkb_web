<?php
include '../config/config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengamankan data input
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

    // Proses upload gambar jika ada
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    $gambar = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        $target_file = 'uploads/' . basename($_FILES['gambar']['name']);
        
        // Validasi tipe file dan ukuran
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = $target_file;
            } else {
                echo "Error: Gagal mengunggah gambar.";
                exit();
            }
        } else {
            echo "File tidak valid atau ukuran terlalu besar.";
            exit();
        }
    }

    // Menyimpan data berita ke database
    $sql = "INSERT INTO berita (judul, konten, kategori, gambar, tanggal_publikasi) VALUES ('$judul', '$konten', '$kategori', '$gambar', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Menambahkan notifikasi untuk admin
        $user_id = $_SESSION['admin']; 
        $sql_notif = "INSERT INTO notifikasi (user_id, tipe, isi) VALUES ('$user_id', 'berita_baru', 'Berita baru menunggu persetujuan')";
        $conn->query($sql_notif);

        // Menambahkan notifikasi untuk admin tentang berita baru
        $isi_notifikasi = "Berita baru ditambahkan: " . $judul;
        $sql_notifikasi = "INSERT INTO notifikasi (user_id, isi_notifikasi) VALUES ('$user_id', '$isi_notifikasi')";
        $conn->query($sql_notifikasi);

        $_SESSION['message'] = "Berita berhasil ditambahkan.";
        header("Location: tambah_berita.php"); // Redirect setelah berhasil
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
