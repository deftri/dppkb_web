<?php
session_start();
include '../config/config.php';

// If the user is already logged in, redirect based on role
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
                            header("Location: ../admin/admin-dashboard.php");
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
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset dan dasar */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background-color: #fff;
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .logo {
            max-width: 100px;
            width: 100%;
            height: auto;
        }

        h2 {
            color: #4a90e2;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #a94442;
            background-color: #f2dede;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 14px;
            text-align: center;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .input-field:focus {
            border-color: #4a90e2;
            outline: none;
        }

        .button {
            background-color: #4a90e2;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .button:hover {
            background-color: #357abd;
        }

        .forgot-password {
            color: #4a90e2;
            font-size: 16px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #357abd;
            text-decoration: underline;
        }

        .back-button {
            background-color: #ddd;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #bbb;
        }

        /* Responsive adjustments untuk tablet dan mobile */
        @media (max-width: 768px) {
            .login-container {
                padding: 25px 20px;
            }

            .logo {
                max-width: 80px;
            }

            h2 {
                font-size: 24px;
            }

            .button {
                font-size: 16px;
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px 15px;
            }

            .logo {
                max-width: 60px;
            }

            h2 {
                font-size: 22px;
            }

            .input-field {
                padding: 10px;
                font-size: 14px;
            }

            .button {
                font-size: 16px;
                padding: 10px;
            }

            .forgot-password {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="../assets/img/sinderela.png" alt="SINDERELA Logo" class="logo"> <!-- Sesuaikan path jika diperlukan -->
        <h2>Login</h2>
        <?php if (!empty($error)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" class="input-field" id="username" name="username" placeholder="Username" required>
            <input type="password" class="input-field" id="password" name="password" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
        </form>
        <a href="register.php" class="forgot-password">Belum punya akun? Klik untuk mendaftar</a>
        <button class="back-button" onclick="window.location.href='../public_html/berita/index.php';">Kembali ke Halaman Index</button>
    </div>
</body>
</html>
