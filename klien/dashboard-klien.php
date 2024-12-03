<?php
session_start();
include '../config/config.php';

// Pastikan pengguna sudah login dan memiliki peran 'klien'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'klien') {
    header("Location: ../public/login.php");
    exit();
}

$klien_id = $_SESSION['user_id'];
$nama = isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Klien';

// Inisialisasi variabel pesan feedback
$feedback_message = '';
$feedback_type = '';

// Cek sesi yang aktif atau menunggu
$sql_sesi = "SELECT * FROM chat_sessions WHERE klien_id = ? AND status IN ('menunggu', 'berlangsung')";
$stmt_sesi = $conn->prepare($sql_sesi);
$stmt_sesi->bind_param("i", $klien_id);
$stmt_sesi->execute();
$sesi_aktif = $stmt_sesi->get_result()->fetch_assoc();

// Ambil wilayah yang tersedia jika tidak ada sesi aktif
if (!$sesi_aktif) {
    $sql_wilayah = "SELECT DISTINCT id, nama_wilayah 
                    FROM wilayah 
                    WHERE id IN (
                        SELECT id_wilayah FROM users 
                        WHERE role = 'konselor' AND is_online = 1
                    )";
    $result_wilayah = $conn->query($sql_wilayah);
}

// Handle form untuk memulai sesi konseling baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle memulai sesi konseling
    if (isset($_POST['id_wilayah'], $_POST['sub_role'])) {
        $id_wilayah = filter_var($_POST['id_wilayah'], FILTER_SANITIZE_NUMBER_INT);
        $sub_role = htmlspecialchars(trim($_POST['sub_role']), ENT_QUOTES, 'UTF-8');

        // Validasi input
        if (empty($id_wilayah) || empty($sub_role)) {
            $feedback_message = "Silakan pilih wilayah dan jenis konselor.";
            $feedback_type = "danger";
        } else {
            // Cari konselor yang sesuai dengan wilayah dan sub-role
            $sql_get_konselor = "SELECT id FROM users 
                                 WHERE role = 'konselor' 
                                   AND is_online = 1 
                                   AND id_wilayah = ? 
                                   AND sub_role = ? 
                                 LIMIT 1";
            $stmt_konselor = $conn->prepare($sql_get_konselor);
            $stmt_konselor->bind_param("is", $id_wilayah, $sub_role);
            $stmt_konselor->execute();
            $konselor = $stmt_konselor->get_result()->fetch_assoc();

            if (!$konselor) {
                $feedback_message = "Saat ini tidak ada konselor yang tersedia karena sedang dalam sesi. Mohon coba lagi nanti ";
                $feedback_type = "danger";
            } else {
                $konselor_id = $konselor['id'];

                // Update sub_role klien berdasarkan pilihan
                $sql_update_client_sub_role = "UPDATE users SET sub_role = ? WHERE id = ?";
                $stmt_update_sub_role = $conn->prepare($sql_update_client_sub_role);
                $stmt_update_sub_role->bind_param("si", $sub_role, $klien_id);
                $stmt_update_sub_role->execute();

                // Mulai sesi baru
                $sql_new_session = "INSERT INTO chat_sessions (klien_id, konselor_id, status, id_wilayah) VALUES (?, ?, 'menunggu', ?)";
                $stmt_new_session = $conn->prepare($sql_new_session);
                $stmt_new_session->bind_param("iii", $klien_id, $konselor_id, $id_wilayah);
                if ($stmt_new_session->execute()) {
                    $new_session_id = $conn->insert_id;
                    header("Location: ../public/chat_room.php?session_id=$new_session_id");
                    exit();
                } else {
                    $feedback_message = "Gagal memulai sesi konseling. Silakan coba lagi.";
                    $feedback_type = "danger";
                }
            }
        }
    }



    // Handle pengiriman rating untuk SINDERELA
    if (isset($_POST['sinderela_rating'])) {
        $rating = filter_var($_POST['sinderela_rating'], FILTER_VALIDATE_INT);
        $feedback = htmlspecialchars(trim($_POST['sinderela_feedback']), ENT_QUOTES, 'UTF-8');

        if ($rating === false || $rating < 1 || $rating > 5) {
            $feedback_message = "Rating SINDERELA harus antara 1 hingga 5.";
            $feedback_type = "danger";
        } else {
            // Masukkan ke tabel rating_sinderela
            $stmt = $conn->prepare('INSERT INTO rating_sinderela (user_id, rated_by_id, rating, feedback) VALUES (?, ?, ?, ?)');
            $stmt->bind_param("iiis", $klien_id, $klien_id, $rating, $feedback);

            if ($stmt->execute()) {
                $feedback_message = "Rating SINDERELA berhasil dikirim.";
                $feedback_type = "success";
            } else {
                $feedback_message = "Gagal mengirim rating SINDERELA. Silakan coba lagi.";
                $feedback_type = "danger";
            }
        }
    }

    // Handle pengiriman rating Konselor
    if (isset($_POST['konselor_rating'], $_POST['konselor_id'])) {
        $rating = filter_var($_POST['konselor_rating'], FILTER_VALIDATE_INT);
        $feedback = htmlspecialchars(trim($_POST['konselor_feedback']), ENT_QUOTES, 'UTF-8');
        $konselor_id = filter_var($_POST['konselor_id'], FILTER_SANITIZE_NUMBER_INT);

        if ($rating === false || $rating < 1 || $rating > 5) {
            $feedback_message = "Rating konselor harus antara 1 hingga 5.";
            $feedback_type = "danger";
        } elseif (empty($konselor_id)) {
            $feedback_message = "ID konselor tidak valid.";
            $feedback_type = "danger";
        } else {
            // Masukkan ke tabel rating_konselor
            $stmt = $conn->prepare('INSERT INTO rating_konselor (klien_id, konselor_id, rating, feedback) VALUES (?, ?, ?, ?)');
            $stmt->bind_param("iiis", $klien_id, $konselor_id, $rating, $feedback);

            if ($stmt->execute()) {
                $feedback_message = "Rating dan feedback konselor berhasil dikirim.";
                $feedback_type = "success";
            } else {
                $feedback_message = "Gagal mengirim rating dan feedback konselor. Silakan coba lagi.";
                $feedback_type = "danger";
            }
        }
    }
}

// Ambil data konselor untuk rating (hanya konselor yang pernah dikonsultasikan)
$sql_konselor = "SELECT DISTINCT u.id, u.username 
                 FROM users u 
                 JOIN chat_sessions cs ON cs.konselor_id = u.id 
                 WHERE cs.klien_id = ?";
$stmt_konselor = $conn->prepare($sql_konselor);
$stmt_konselor->bind_param("i", $klien_id);
$stmt_konselor->execute();
$konselor_result = $stmt_konselor->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klien</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #d4f1f4, #a6c0fe);
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
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            padding-bottom: 20px;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #28a745; /* Hijau */
            color: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        /* Logo */
        .navbar-custom .logo {
            width: 60px;
            height: auto;
        }

        /* Logout Button */
        .navbar-custom .logout-button {
            background-color: #dc3545; /* Merah */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .navbar-custom .logout-button:hover {
            background-color: #c82333;
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
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.5rem;
            text-align: center;
            font-weight: 600;
        }

        /* Feedback Alerts */
        .alert-feedback {
            margin-top: 20px;
        }

        /* Card Styling */
        .card-custom {
            background-color: #f8f9fa;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .card-custom h5 {
            margin-bottom: 20px;
            color: #007bff;
            font-weight: 600;
        }

        /* Rating Section */
        .rating-section {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .rating-card {
            flex: 1;
            min-width: 320px;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .rating-card h5 {
            margin-bottom: 15px;
            color: #007bff;
        }

        .rating-star label {
            font-size: 1.5rem;
            color: #000;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s;
        }

        .rating-star input[type="radio"] {
            display: none;
        }

        .rating-star label:hover,
        .rating-star label:hover ~ label,
        .rating-star input[type="radio"]:checked ~ label {
            color: #ffc107;
            transform: scale(1.2);
        }

        /* Buttons */
        .btn-primary, .btn-success {
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover, .btn-success:hover {
            transform: translateY(-3px);
        }

        .logout-button-full {
            background-color: #ff5a5a;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            font-weight: bold;
            transition: 0.3s ease-in-out;
            cursor: pointer;
        }

        .logout-button-full:hover {
            background-color: #ff3333;
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

            .rating-star label {
                font-size: 1.3rem;
            }

            .rating-card {
                padding: 10px;
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

            .rating-star label {
                font-size: 1.2rem;
            }

            .rating-card {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-dashboard">
        <!-- Navbar -->
        <div class="navbar-custom d-flex">
            <!-- Logo -->
            <img src="../assets/img/sinderela.png" alt="Logo Sinderela" class="logo">
            
            <!-- Logout Button -->
            <button class="logout-button btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <!-- Main Content -->
      <div class="content-area">
    <!-- User Information -->
    <div class="user-info">
                <!-- Menampilkan pesan selamat datang dengan nama klien -->
                <?php 
                    // Pastikan nama klien ada dalam session
                    if (!empty($nama)) {
                        echo "Selamat datang, " . htmlspecialchars($nama);  // Gunakan $nama, bukan $nama_users
                    } else {
                        echo "Selamat datang, Klien";  // Default jika nama kosong
                    }
                ?>

            </div>
        </div>


            <!-- Alert Feedback -->
            <?php if ($feedback_message): ?>
                <div class="alert alert-<?= $feedback_type ?> alert-dismissible fade show alert-feedback" role="alert">
                    <?= htmlspecialchars($feedback_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <!-- Form untuk memilih wilayah atau melanjutkan sesi aktif -->
            <div class="card-custom">
                <?php if (!$sesi_aktif): ?>
                    <h5 class="card-title">Mulai Sesi Konseling</h5>
                    <?php if ($result_wilayah->num_rows > 0): ?>
                        <form action="dashboard-klien.php" method="POST">
                            <!-- Pilihan Wilayah -->
                            <div class="mb-3">
                                <label for="id_wilayah" class="form-label">Pilih Wilayah</label>
                                <select class="form-select" id="id_wilayah" name="id_wilayah" required>
                                    <option value="">-- Pilih Wilayah --</option>
                                    <?php while ($wilayah = $result_wilayah->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($wilayah['id']) ?>">
                                            <?= htmlspecialchars($wilayah['nama_wilayah']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Pilihan Jenis Konselor -->
                            <div class="mb-3">
                                <label for="sub_role" class="form-label">Pilih Jenis Konselor</label>
                                <select class="form-select" id="sub_role" name="sub_role" required>
                                    <option value="">-- Pilih Jenis Konselor --</option>
                                    <option value="Sebaya">Konselor Sebaya</option>
                                    <option value="Dewasa">Konselor Dewasa</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Mulai Sesi Konseling</button>
                        </form>
                    <?php else: ?>
                        <p class="text-danger">Tidak ada wilayah yang tersedia saat ini.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Jika ada sesi aktif -->
                    <h5 class="card-title">Sesi Konseling Aktif</h5>
                    <a href="../public/chat_room.php?session_id=<?= htmlspecialchars($sesi_aktif['id']) ?>" class="btn btn-success w-100">
                        <i class="fas fa-comments me-2"></i> Lanjutkan Sesi Konseling
                    </a>
                <?php endif; ?>
            </div>

            <!-- Rating Section -->
            <div class="rating-section">
                <!-- Rating SINDERELA -->
                <div class="rating-card">
                    <h5>Berikan Rating SINDERELA</h5>
                    <form method="POST" action="dashboard-klien.php">
                        <div class="rating-star mb-3">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="sinderela_rating" value="<?= $i ?>" id="sinderela_star<?= $i ?>" required>
                                <label for="sinderela_star<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="sinderela_feedback" rows="3" placeholder="Tambahkan feedback..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Rating</button>
                    </form>
                </div>

                <!-- Rating Konselor -->
                <div class="rating-card">
                    <h5>Berikan Rating Konselor</h5>
                    <form method="POST" action="dashboard-klien.php">
                        <div class="mb-3">
                            <label for="konselor_id" class="form-label">Pilih Konselor</label>
                            <select class="form-select" id="konselor_id" name="konselor_id" required>
                                <option value="">-- Pilih Konselor --</option>
                                <?php while ($konselor = $konselor_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($konselor['id']) ?>"><?= htmlspecialchars($konselor['username']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="rating-star mb-3">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="konselor_rating" value="<?= $i ?>" id="konselor_star<?= $i ?>" required>
                                <label for="konselor_star<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="konselor_feedback" rows="3" placeholder="Feedback untuk konselor..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Rating</button>
                    </form>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="d-flex justify-content-center">
                <button onclick="window.location.href='logout.php'" class="logout-button-full btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS dan Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Optional JavaScript for additional interactivity
    </script>
</body>
</html>
