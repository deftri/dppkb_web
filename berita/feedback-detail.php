<?php
include '../config/config.php'; // Menyambungkan ke database

// Mengecek apakah ada parameter ID yang dikirimkan melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Feedback tidak ditemukan.");
}

// Mengambil ID dari parameter URL
$id = $_GET['id'];

// Query untuk mengambil data feedback berdasarkan ID
$sql = "SELECT * FROM messages_feedback WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Jika tidak ada data yang ditemukan
if ($result->num_rows == 0) {
    die("Feedback tidak ditemukan.");
}

// Menyimpan data feedback dalam variabel
$feedback = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Feedback - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f8fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1200px;
        }
        h2 {
            font-size: 2rem;
            color: #333;
        }
        .feedback-details {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .feedback-details .field-label {
            font-weight: bold;
            color: #555;
        }
        .feedback-details .field-value {
            color: #333;
            margin-bottom: 15px;
        }
        .btn-custom {
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-info:hover, .btn-danger:hover, .btn-secondary:hover {
            opacity: 0.8;
        }
        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Detail Feedback</h2>

    <!-- Menampilkan detail feedback -->
    <div class="feedback-details">
        <p class="field-label">Nama:</p>
        <p class="field-value"><?= htmlspecialchars($feedback['name']); ?></p>

        <p class="field-label">Email:</p>
        <p class="field-value"><?= htmlspecialchars($feedback['email']); ?></p>

        <p class="field-label">Subjek:</p>
        <p class="field-value"><?= htmlspecialchars($feedback['subject']); ?></p>

        <p class="field-label">Pesan:</p>
        <p class="field-value"><?= nl2br(htmlspecialchars($feedback['message'])); ?></p>

        <p class="field-label">Tanggal Kirim:</p>
        <p class="field-value"><?= date('d-m-Y H:i', strtotime($feedback['created_at'])); ?></p>

        <p class="field-label">Status:</p>
        <p class="field-value">
            <?php
            if ($feedback['status'] == 'pending') {
                echo '<span class="badge bg-warning text-dark">Pending</span>';
            } else {
                echo '<span class="badge bg-success text-white">Dibaca</span>';
            }
            ?>
        </p>

        <!-- Tombol Aksi -->
        <div class="action-buttons mt-4">
            <?php if ($feedback['status'] == 'pending'): ?>
                <a href="mark-as-read.php?id=<?= $feedback['id']; ?>" class="btn btn-info btn-custom">
                    <i class="fas fa-check"></i> Tandai sebagai Dibaca
                </a>
            <?php endif; ?>

            <a href="delete-feedback.php?id=<?= $feedback['id']; ?>" class="btn btn-danger btn-custom" onclick="return confirm('Apakah Anda yakin ingin menghapus feedback ini?');">
                <i class="fas fa-trash"></i> Hapus Feedback
            </a>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="back-btn text-center">
        <a href="feedback-admin.php" class="btn btn-secondary btn-custom">Kembali ke Daftar Feedback</a>
    </div>
</div>

<!-- JS Bootstrap dan dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
