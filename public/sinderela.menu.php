<?php
session_start();

// Include your database configuration file here
include '../config/config.php';

// If the user is already logged in, redirect based on their role
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'klien':
            header("Location: ../klien/dashboard-klien.php");
            break;
        case 'konselor':
            header("Location: ../konselor/dashboard-konselor.php");
            break;
        case 'psikolog':
            header("Location: ../psikolog/dashboard-psikolog.php");
            break;
        case 'admin':
            header("Location: ../admin/dashboard-admin.php");
            break;
        default:
            header("Location: ../public/login.php");
            break;
    }
    exit();
}

// Login logic
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, role, password_hash, id_wilayah FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_wilayah'] = $user['id_wilayah'];

                // Update user status to online
                $update_sql = "UPDATE users SET is_online = 1 WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $user['id']);
                
                if ($update_stmt->execute()) {
                    switch ($user['role']) {
                        case 'klien':
                            header("Location: ../klien/dashboard-klien.php");
                            break;
                        case 'konselor':
                            header("Location: ../konselor/dashboard-konselor.php");
                            break;
                        case 'psikolog':
                            header("Location: ../psikolog/dashboard-psikolog.php");
                            break;
                        case 'admin':
                            header("Location: ../admin/dashboard-admin.php");
                            break;
                        default:
                            header("Location: ../public/login.php");
                            break;
                    }
                    exit();
                } else {
                    $error = "Terjadi kesalahan saat mengupdate status online.";
                }
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    } else {
        $error = "Terjadi kesalahan pada query.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Sinderela</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #d4f1f4, #a6c0fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            overflow: hidden;
        }

        /* Container Styling */
        .container-menu {
            text-align: center;
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 50px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        /* Logo Styling */
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        /* Title Styling */
        .container-menu h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
            color: #007bff;
        }

        .container-menu p {
            font-size: 1rem;
            margin-bottom: 30px;
            color: #555;
        }

        /* Button Styling */
        .btn-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: #fff;
            background-color: #28a745; /* Hijau */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
        }

        .btn-custom.btn-register {
            background-color: #007bff; /* Biru */
        }

        .btn-custom:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-custom.btn-register:hover {
            background-color: #0069d9;
        }

        .btn-custom:active {
            transform: translateY(0);
            background-color: #1e7e34;
        }

        /* Icon Styling */
        .btn-custom .fa {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Responsive Typography */
        @media (max-width: 576px) {
            .container-menu {
                padding: 40px 20px;
            }

            .container-menu h1 {
                font-size: 1.75rem;
            }

            .container-menu p {
                font-size: 0.95rem;
            }

            .btn-custom {
                font-size: 1rem;
                padding: 10px 16px;
            }

            .btn-custom .fa {
                font-size: 1rem;
                margin-right: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container-menu">
        <!-- Logo -->
        <img src="../assets/img/sinderela.png" alt="Logo Sinderela" class="logo">
        
        <!-- Title and Description -->
        <h1>Selamat Datang di <span>LAYANAN SINDERELA</span></h1>
        <p>Silakan pilih menu di bawah untuk memulai konseling Anda.</p>

        <!-- Buttons -->
        <a href="../public/login.php" class="btn btn-custom btn-start-chat">
            <i class="fas fa-comments"></i> Mulai Chat
        </a>
        <a href="../public/register.php" class="btn btn-custom btn-register">
            <i class="fas fa-user-plus"></i> Register
        </a>

        <!-- Back to Dashboard Button -->
        <?php if (isset($_SESSION['role'])): ?>
            <a href=" 
                <?php 
                    switch ($_SESSION['role']) {
                        case 'klien':
                            echo '../berita/index.php';
                            break;
                        case 'konselor':
                            echo '../berita/index.php';
                            break;
                        case 'psikolog':
                            echo '../berita/index.php';
                            break;
                        case 'admin':
                            echo '../berita/index.php';
                            break;
                        default:
                            echo '#';
                            break;
                    }
                ?>" class="btn btn-custom">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger mt-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
