<?php
session_start();
include '../config/config.php';

// Pastikan pengguna sudah login dan memiliki peran psikolog
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psikolog') {
    header("Location: ../public/login.php");
    exit();
}

$psikolog_id = $_SESSION['user_id'];

// Tangani penyelesaian sesi oleh psikolog

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['end_session_id'])) {
    // Filter dan validasi ID sesi yang akan diakhiri
    $end_session_id = filter_var($_POST['end_session_id'], FILTER_SANITIZE_NUMBER_INT);

    // Pastikan `psikolog_id` diambil dari sesi login pengguna

    // Perbarui status sesi menjadi "selesai"
    $sql_end_session = "UPDATE chat_sessions SET status = 'selesai' WHERE id = ? AND psikolog_id = ?";
    $stmt_end_session = $conn->prepare($sql_end_session);
    $stmt_end_session->bind_param("ii", $end_session_id, $psikolog_id);

    if ($stmt_end_session->execute()) {
        if ($stmt_end_session->affected_rows > 0) {
            echo "<script>alert('Sesi berhasil diselesaikan.'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Gagal menyelesaikan sesi. Mungkin ID sesi tidak valid atau Anda tidak memiliki izin.');</script>";
        }
    } else {
        echo "<script>alert('Terjadi kesalahan pada server. Silakan coba lagi.');</script>";
    }
}


// Ambil sesi rujukan aktif yang dialokasikan untuk psikolog ini (refer = 1)
$sql_referred_sessions = "SELECT cs.*, u.username AS klien_username, w.nama_wilayah
                          FROM chat_sessions cs
                          JOIN users u ON cs.klien_id = u.id
                          JOIN wilayah w ON u.id_wilayah = w.id
                          WHERE cs.status = 'berlangsung' AND cs.refer = 1 AND cs.psikolog_id = ?";
$stmt_referred_sessions = $conn->prepare($sql_referred_sessions);
$stmt_referred_sessions->bind_param("i", $psikolog_id);
$stmt_referred_sessions->execute();
$referred_sessions_result = $stmt_referred_sessions->get_result();


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Psikolog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom CSS Styling */

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #3a7bd5;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .logout-button {
            background-color: #ff5e5e;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #ff1e1e;
        }

        .content {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-size: 22px;
            color: #3a7bd5;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f0f4f7;
            color: #333;
            font-weight: 600;
            font-size: 16px;
        }

        table td {
            color: #555;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .start-session, .end-session-button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .start-session {
            background-color: #56c596;
            color: white;
        }

        .start-session:hover {
            background-color: #3da573;
        }

        .end-session-button {
            background-color: #ff5e5e;
            color: white;
        }

        .end-session-button:hover {
            background-color: #c9302c;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 16px;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .navbar h2 {
                font-size: 20px;
            }

            .logout-button {
                padding: 6px 12px;
                font-size: 12px;
            }

            .content h2 {
                font-size: 20px;
            }

            table th, table td {
                padding: 10px 12px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .start-session, .end-session-button {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar h2 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .logout-button {
                width: 100%;
            }

            .content h2 {
                font-size: 18px;
            }

            table th, table td {
                padding: 8px 10px;
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .start-session, .end-session-button {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
    <h2>Dashboard Psikolog</h2>
    <div style="display: flex; align-items: center; gap: 15px;">
        <!-- Ikon Notifikasi -->
        <div id="notification-icon" style="position: relative;">
            <i class="fas fa-bell" style="font-size: 24px; cursor: pointer;"></i>
            <span id="notification-count" style="position: absolute; top: -5px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; display: none;">0</span>
        </div>
        <!-- Tombol Logout -->
        <button class="logout-button" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>


    <!-- Content Wrapper -->
    <div class="content">
        <h2>Sesi Rujukan Aktif</h2>
        
        <!-- Tabel Sesi Rujukan -->
        <?php if ($referred_sessions_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Sesi</th>
                            <th>Nama Klien</th>
                            <th>Wilayah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($session = $referred_sessions_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($session['id']) ?></td>
                                <td><?= htmlspecialchars($session['klien_username']) ?></td>
                                <td><?= htmlspecialchars($session['nama_wilayah']) ?></td>
                                <td><?= htmlspecialchars($session['status']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="../public/chat_room.php?session_id=<?= htmlspecialchars($session['id']) ?>" class="start-session btn btn-sm">
                                            <i class="fas fa-comments"></i> Lanjutkan
                                        </a>
                                        <!-- Tombol Selesaikan Sesi -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="end_session_id" value="<?= htmlspecialchars($session['id']) ?>">
                                            <button type="submit" class="end-session-button btn btn-sm">
                                                <i class="fas fa-times-circle"></i> Selesaikan Sesi
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">Tidak ada sesi rujukan aktif saat ini.</div>
        <?php endif; ?>

        <audio id="notification-sound" src="../assets/sounds/notif_messages.mp3" preload="auto"></audio>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const notificationSound = document.getElementById('notification-sound');

    // Atur volume ke 50%
    notificationSound.volume = 0.4;

    console.log('Volume diatur ke 50%');
});

</script>
       


        <script>
// Variabel untuk melacak jumlah pesan sebelumnya
let previousMessageCount = 0;

// Fungsi untuk mengambil notifikasi baru
function fetchNotifications() {
    fetch('fetch_notifications.php')
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === 'success') {
                const notificationCount = document.getElementById('notification-count');
                const count = data.new_messages;
                const notificationSound = document.getElementById('notification-sound');

                if (count > 0) {
                    notificationCount.textContent = count;
                    notificationCount.style.display = 'block';

                    // Mainkan suara jika ada pesan baru
                    if (count > previousMessageCount) {
                        notificationSound.play().catch((err) => {
                            console.error('Error playing notification sound:', err);
                        });
                    }
                } else {
                    notificationCount.style.display = 'none';
                }

                previousMessageCount = count;
            } else {
                console.error('Error fetching notifications:', data.message);
            }
        })
        .catch((error) => console.error('Fetch error:', error));
}


// Panggil fungsi setiap 5 detik
setInterval(fetchNotifications, 5000);

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchNotifications);
</script>


    </div>

    <!-- Bootstrap JS dan Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
