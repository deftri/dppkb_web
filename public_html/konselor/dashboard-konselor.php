<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/config.php';

// Pastikan pengguna sudah login dan memiliki peran 'konselor'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'konselor') {
    header("Location: ../public/login.php");
    exit();
}

$konselor_id = $_SESSION['user_id'];

// Fetch counselor’s name and area
$sql_user = "SELECT username, id_wilayah, sub_role FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $konselor_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();

if (!$user_data) {
    echo "Data konselor tidak ditemukan.";
    exit();
}

$username = htmlspecialchars($user_data['username']);
$id_wilayah = $user_data['id_wilayah'];
$sub_role = $user_data['sub_role']; // Ambil sub_role konselor

// Fetch list of online psychologists
$sql_online_psychologists = "SELECT id, username FROM users WHERE role = 'psikolog' AND is_online = 1";
$stmt_online_psychologists = $conn->prepare($sql_online_psychologists);
$stmt_online_psychologists->execute();
$result_online_psychologists = $stmt_online_psychologists->get_result();
$online_psychologists = [];

while ($row = $result_online_psychologists->fetch_assoc()) {
    $online_psychologists[] = $row; // Menyimpan id dan username psikolog
}

// Fetch active sessions
// Query untuk mengambil sesi aktif yang belum dirujuk
$sql_sesi_berlangsung = "SELECT cs.* 
                         FROM chat_sessions cs 
                         JOIN users u ON cs.klien_id = u.id 
                         WHERE cs.konselor_id = ? 
                           AND cs.status IN ('menunggu', 'berlangsung') 
                           AND cs.refer = 0
                           AND (u.sub_role = ? OR u.sub_role IS NULL)";
$stmt_sesi_berlangsung = $conn->prepare($sql_sesi_berlangsung);
$stmt_sesi_berlangsung->bind_param("is", $konselor_id, $sub_role);
$stmt_sesi_berlangsung->execute();
$sesi_berlangsung_result = $stmt_sesi_berlangsung->get_result();


if ($sesi_berlangsung_result->num_rows === 0) {
    error_log("Tidak ada sesi aktif atau menunggu untuk konselor $konselor_id dengan sub_role $sub_role.");
}

// Handle session referral to psychologist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refer_session_id'])) {
    $refer_session_id = filter_var($_POST['refer_session_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!$refer_session_id || !is_numeric($refer_session_id)) {
        echo "<script>showBubble('ID sesi tidak valid.', 'error');</script>";
        exit();
    }

    // Daftar psikolog yang tersedia (urutkan sesuai prioritas)
    $psychologist_ids = [100, 101, 102, 103];
    $placeholders = implode(',', array_fill(0, count($psychologist_ids), '?'));

    // Check if any of the available psychologists are not busy
    // Pastikan Anda mengambil id psikolog yang online dengan benar.
    $sql_check_busy = "
        SELECT psikolog_id, COUNT(*) AS active_sessions 
        FROM chat_sessions 
        WHERE psikolog_id IN (" . implode(',', array_map('intval', $online_psychologists)) . ") 
        AND status IN ('menunggu', 'berlangsung')
        GROUP BY psikolog_id
    ";
    $stmt_check = $conn->prepare($sql_check_busy);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();


    $busy_psychologists = [];
    while ($row = $result_check->fetch_assoc()) {
        if ($row['active_sessions'] > 0) {
            $busy_psychologists[] = $row['psikolog_id'];
        }
    }

    // Find an available psychologist
    $available_psychologist = null;
    foreach ($online_psychologists as $psychologist) {
        if (!in_array($psychologist['id'], $busy_psychologists)) {
            $available_psychologist = $psychologist['id'];
            break;
        }
    }

    if (!$available_psychologist) {
        error_log("Semua psikolog online sedang sibuk.");
        echo "<script>showBubble('Semua psikolog online sedang sibuk. Silakan coba lagi nanti.', 'error');</script>";
        exit();
    }

    // Jika ditemukan psikolog yang tersedia, lakukan update
    // Update session to refer to the available psychologist
    $sql_refer_session = "UPDATE chat_sessions SET status = 'berlangsung', refer = 1, psikolog_id = ? WHERE id = ? AND konselor_id = ?";
    $stmt_refer_session = $conn->prepare($sql_refer_session);
    $stmt_refer_session->bind_param("iii", $available_psychologist, $refer_session_id, $konselor_id);
    $stmt_refer_session->execute();

    if ($stmt_refer_session->affected_rows > 0) {
        echo "<script>showBubble('Sesi berhasil dirujuk ke psikolog ID $available_psychologist.', 'success');</script>";
    } else {
        error_log("Gagal merujuk sesi ID $refer_session_id ke psikolog ID $available_psychologist.");
        echo "<script>showBubble('Gagal merujuk sesi. Silakan coba lagi.', 'error');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Konselor</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <div id="psychologist-notification" style="display: none; position: fixed; bottom: 20px; right: 20px; background-color: #4caf50; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
    <strong>Notifikasi:</strong> Ada psikolog yang online!
</div>
<audio id="notification-sound" src="../assets/sounds/notif_messages.mp3" preload="auto"></audio>
    <style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #e0f7fa, #b2ebf2); /* Warna lebih lembut */
    font-family: 'Poppins', sans-serif;
    color: #333;
    padding: 20px;
    min-height: 100vh;
}

/* Container Styling */
.container-dashboard {
    width: 100%;
    max-width: 1200px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); /* Lebih halus */
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    padding-bottom: 20px;
    overflow: hidden;
}

/* Navbar */
.navbar-custom {
    background-color: #0288d1; /* Warna biru lebih terang */
    color: #fff;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 4px solid #0277bd;
}

.navbar-custom .logo {
    width: 60px;
    height: auto;
}

.navbar-custom .logout-button {
    background-color: #ff5252; /* Merah terang */
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease-in-out;
}

.navbar-custom .logout-button:hover {
    background-color: #e53935;
    transform: scale(1.05);
}

/* Content Area */
.content-area {
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* User Information */
.user-info {
    background: linear-gradient(90deg, #0288d1, #0277bd);
    color: white;
    padding: 20px;
    border-radius: 8px;
    font-size: 1.6rem;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Table Styling */
table {
    table-layout: fixed;
    width: 100%;
}

table th, table td {
    text-align: center; /* Untuk merapikan teks */
}

td.status-column {
    width: 150px; /* Atur sesuai kebutuhan */
    white-space: nowrap; /* Cegah teks menjadi multi-baris */
}

th {
    background-color: #f5f5f5;
    color: #333;
    font-weight: 700;
    font-size: 16px;
}

td {
    color: #555;
    font-size: 14px;
}

tr:hover td {
    background-color: #e3f2fd; /* Warna hover lebih lembut */
    transition: background-color 0.3s ease;
}

/* Buttons */
.button {
    padding: 8px 14px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease-in-out;
}

.start-session {
    background-color: #4caf50; /* Hijau terang */
    color: #fff;
}

.start-session:hover {
    background-color: #388e3c;
    transform: translateY(-2px);
}

.refer-button {
    background-color: #03a9f4; /* Biru terang */
    color: #fff;
}

.refer-button:hover {
    background-color: #0288d1;
    transform: translateY(-2px);
}

.download-chat {
    background-color: #2196f3; /* Biru */
    color: #fff;
}

.download-chat:hover {
    background-color: #1976d2;
    transform: scale(1.05);
}

.delete-session {
    background-color: #ff5252; /* Merah */
    color: #fff;
}

.delete-session:hover {
    background-color: #e53935;
    transform: translateY(-2px);
}

/* No Data Message */
.no-data {
    text-align: center;
    font-style: italic;
    color: #888;
    padding: 20px 0;
}

/* Tambahan untuk indikator status */
.status-indicator {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
}

.status-indicator.available {
    background-color: #4caf50; /* Hijau */
}

.status-indicator.busy {
    background-color: #ffeb3b; /* Kuning */
    color: #333; /* Teks kontras dengan kuning */
}

.status-indicator.offline {
    background-color: #f44336; /* Merah */
}

#notification-icon {
    position: relative;
    cursor: pointer;
}

#notification-icon i {
    transition: transform 0.3s ease;
}

#notification-icon:hover i {
    transform: scale(1.1);
}

#notification-count {
    position: absolute;
    top: -5px;
    right: -10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    display: none;
}




/* Responsive Styling */
@media (max-width: 768px) {
    .navbar-custom .logo {
        width: 50px;
    }

    .content-area {
        padding: 20px;
        gap: 20px;
    }

    table td {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .navbar-custom .logo {
        width: 40px;
    }

    .logout-button {
        width: 100%;
        justify-content: center;
    }

    .user-info {
        font-size: 1.2rem;
    }

    .action-buttons {
        flex-direction: column;
        align-items: flex-start;
    }
    @media (max-width: 480px) {
    .navbar-custom {
        flex-direction: column;
        align-items: flex-start;
    }

    #notification-icon {
        margin-bottom: 10px;
    }

    .logout-button {
        margin-top: 10px;
    }
}

}
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const notificationBox = document.getElementById('psychologist-notification');
    let previousOnlineCount = 0;

    function fetchPsychologists() {
        fetch('check_psychologist_status.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const onlineCount = data.psychologists.length;

                    // Tampilkan notifikasi jika ada psikolog yang online
                    if (onlineCount > 0 && onlineCount !== previousOnlineCount) {
                        notificationBox.style.display = 'block';
                        notificationBox.innerHTML = `<strong>Notifikasi:</strong> ${onlineCount} psikolog online sekarang!`;
                        
                        // Sembunyikan notifikasi setelah 5 detik
                        setTimeout(() => {
                            notificationBox.style.display = 'none';
                        }, 5000);
                    }

                    previousOnlineCount = onlineCount;
                } else {
                    console.error('Error fetching psychologists:', data.message);
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }

    // Panggil fungsi setiap 5 detik
    setInterval(fetchPsychologists, 5000);

    // Panggil fungsi saat halaman dimuat
    fetchPsychologists();
});

document.addEventListener('DOMContentLoaded', () => {
    const notificationCount = document.getElementById('notification-count');
    const notificationSound = document.getElementById('notification-sound');
    let previousMessageCount = 0;

    // Fungsi untuk mengambil notifikasi baru
    function fetchNotifications() {
        fetch('fetch_notifications.php')
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debugging: Pastikan data yang diterima benar
        if (data.status === 'success') {
            const count = data.notifications.length;
            // Perbarui jumlah notifikasi
            if (count > 0) {
                notificationCount.textContent = count;
                notificationCount.style.display = 'block';
                // Mainkan suara hanya jika ada notifikasi baru
                if (count > previousMessageCount) {
                    notificationSound.play();
                }
            } else {
                notificationCount.style.display = 'none';
            }
            previousMessageCount = count;
        } else {
            console.error('Error fetching notifications:', data.message);
        }
    })
    .catch(error => console.error('Fetch error:', error));

    }

    // Panggil fungsi setiap 5 detik
    setInterval(fetchNotifications, 5000);

    // Panggil fungsi saat halaman dimuat
    fetchNotifications();
});


</script>

</head>
<body>
    <div class="container-dashboard">
        <!-- Navbar -->
        <div class="navbar-custom d-flex align-items-center justify-content-between">
    <div>
        <img src="../assets/img/sinderela.png" alt="Logo Sinderela" class="logo">
    </div>
    <div class="d-flex align-items-center">
        <!-- Ikon Lonceng -->
        <div id="notification-icon" style="position: relative; cursor: pointer; margin-right: 20px;">
            <i class="fas fa-bell" style="font-size: 24px;"></i>
            <span id="notification-count" style="
                position: absolute;
                top: -5px;
                right: -10px;
                background-color: red;
                color: white;
                border-radius: 50%;
                padding: 2px 6px;
                font-size: 12px;
                display: none;">0</span>
        </div>

        <!-- Tombol Logout -->
        <button class="logout-button btn" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>


        <!-- Main Content -->
        <div class="content-area">
            <!-- User Information -->
            <div class="user-info">
                Selamat Datang, <?= $username ?>
            </div>

            <!-- Online Clients List -->
            <div class="column">

            <h2 style="text-align: center; font-weight: bold;">Status Psikolog</h2>

    <?php
$sql_psikolog = "
    SELECT 
        id, 
        nama, 
        is_online,
        (SELECT COUNT(*) 
         FROM chat_sessions 
         WHERE psikolog_id = users.id 
           AND status IN ('berlangsung', 'menunggu')) AS active_sessions
    FROM users
    WHERE role = 'psikolog';
";
$result_psikolog = $conn->query($sql_psikolog);

if ($result_psikolog && $result_psikolog->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Psikolog</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($psikolog = $result_psikolog->fetch_assoc()): ?>
    <?php
    // Default status
    $status_class = 'offline'; // Default merah
    $status_text = 'Offline';

    // Logika status konselor
    if ($psikolog['is_online'] == 1) {
        if ($psikolog['active_sessions'] > 2) {
            $status_class = 'busy'; // Kuning
            $status_text = 'Sibuk';
        } else {
            $status_class = 'available'; // Hijau
            $status_text = 'Online & Free';
        }
    }
    ?>
    <tr>
        <td style="text-align: left;"><?= htmlspecialchars($psikolog['nama']) ?></td>
        <td>
            <span class="status-indicator <?= $status_class ?>"><?= $status_text ?></span>
        </td>
    </tr>
<?php endwhile; ?>

            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="no-data">Tidak ada psikolog terdaftar.</div>
<?php endif; ?>

</div>


          <!-- Active Sessions -->
          <div class="column">
    <h2>Sesi Aktif</h2>
    <?php if ($sesi_berlangsung_result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Sesi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Rujukan</th>
                        <th>Riwayat Chat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($session = $sesi_berlangsung_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($session['id']) ?></td>
                            <td><?= htmlspecialchars($session['status']) ?></td>
                            <td>
                                <?php if ($session['status'] == 'berlangsung' && $session['refer'] == 1): ?>
                                    <span class="text-muted">Tidak dapat dilanjutkan</span>
                                <?php else: ?>
                                    <a href="../public/chat_room.php?session_id=<?= htmlspecialchars($session['id']) ?>" class="start-session btn btn-success btn-sm">
                                        <i class="fas fa-comments me-1"></i> Lanjutkan
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($session['refer'] == 0 && !empty($online_psychologists)): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="refer_session_id" value="<?= htmlspecialchars($session['id']) ?>">

                                        <!-- Dropdown untuk memilih psikolog -->
                                        <select name="psikolog_id" class="form-select form-select-sm" required>
                                            <option value="" disabled selected>Pilih Psikolog</option>
                                            <?php foreach ($online_psychologists as $psikolog): ?>
                                                <option value="<?= htmlspecialchars($psikolog['id']) ?>"><?= htmlspecialchars($psikolog['username']) ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <!-- Tombol untuk merujuk psikolog -->
                                        <button type="submit" class="refer-button btn btn-info btn-sm mt-2">
                                            <i class="fas fa-share-alt me-1"></i> Rujuk Psikolog
                                        </button>
                                    </form>
                                <?php elseif (empty($online_psychologists)): ?>
                                    <span class="text-muted">Tidak ada psikolog online.</span>
                                <?php else: ?>
                                    <span class="text-success">Sudah dirujuk</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="generate_chat_pdf.php?session_id=<?= htmlspecialchars($session['id']) ?>" class="download-chat btn btn-primary btn-sm" target="_blank">
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                                <a href="javascript:void(0);" onclick="confirmDeleteSession(<?= htmlspecialchars($session['id']) ?>)" class="delete-session btn btn-danger btn-sm">
                                    <i class="fas fa-times-circle me-1"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-data">Tidak ada sesi yang sedang berlangsung atau menunggu.</div>
    <?php endif; ?>
</div>


<!-- Bootstrap JS dan Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Fungsi untuk menghapus klien dari daftar online
    function removeUser(klienId) {
        if (confirm("Apakah Anda yakin ingin menghapus klien ini dari daftar online?")) {
            fetch(`remove_user.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `klien_id=${klienId}`
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Fungsi untuk menghapus sesi
    function confirmDeleteSession(sessionId) {
        if (confirm("Apakah Anda yakin ingin menghapus sesi ini?")) {
            fetch(`delete_session.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `session_id=${sessionId}`
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
</body>
</html>
