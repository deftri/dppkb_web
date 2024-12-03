<?php
// feedback-admin.php
include '../config/config.php'; // Menyambungkan ke database

// Query untuk mengambil data feedback yang statusnya "pending"
$sql = "SELECT * FROM messages_feedback WHERE status = 'pending' ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Masuk - Admin Panel</title>
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
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .badge-status {
            font-weight: 600;
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
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-info:hover, .btn-success:hover, .btn-secondary:hover {
            opacity: 0.8;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Feedback Masuk</h2>

    <!-- Tabel untuk menampilkan pesan feedback -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Tanggal Kirim</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['subject']); ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                        <td>
                            <span class="badge badge-status bg-warning text-dark">Pending</span>
                        </td>
                        <td class="action-buttons">
                            <a href="feedback-detail.php?id=<?= $row['id']; ?>" class="btn btn-info btn-custom" data-bs-toggle="tooltip" title="Lihat detail pesan">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a href="mark-as-read.php?id=<?= $row['id']; ?>" class="btn btn-success btn-custom" data-bs-toggle="tooltip" title="Tandai sebagai dibaca">
                                <i class="fas fa-check"></i> Tandai Dibaca
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada feedback yang masuk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tombol Kembali ke Dashboard -->
    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary btn-custom">Kembali ke Dashboard</a>
    </div>
</div>

<!-- JS Bootstrap dan dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tooltip untuk tombol aksi
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>
