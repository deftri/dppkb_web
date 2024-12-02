<?php
include '../config/config.php';

// Fetch wilayah list from the database
$sql = "SELECT id, nama_wilayah FROM wilayah";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$wilayahList = $result->fetch_all(MYSQLI_ASSOC);

// Success and error message variables
$success = "";
$error = "";

// Registration process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $password = $_POST['password'];
    $id_wilayah = $_POST['id_wilayah'];
    $role = $_POST['role'];
    
    // Automatically set sub_role to 'klien' if role is 'klien'
    $sub_role = ($role === 'klien') ? 'klien' : null;

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Default values for other fields
    $session = null;
    $ratting = 0;
    $is_verified = 0;
    $reset_token = null;
    $token_expiry = null;
    $is_online = 0;
    $email = null;
    $created_at = date('Y-m-d H:i:s');

    // Check if username is already taken
    $sql_check = "SELECT * FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "Username sudah digunakan, silakan pilih yang lain!";
    } else {
        // Insert new user
        $sql = "INSERT INTO users 
                (username, nama, nomor_hp, id_wilayah, password_hash, role, session, ratting, is_verified, reset_token, token_expiry, is_online, email, created_at, sub_role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssisssiississs",
            $username,
            $nama,
            $nomor_hp,
            $id_wilayah,
            $password_hash,
            $role,
            $session,
            $ratting,
            $is_verified,
            $reset_token,
            $token_expiry,
            $is_online,
            $email,
            $created_at,
            $sub_role
        );

        if ($stmt->execute()) {
            $success = "Pendaftaran berhasil! <a href='login.php'>Login di sini</a>.";
        } else {
            $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background-color: #ffffff;
            padding: 30px 25px;
            width: 100%;
            max-width: 700px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .logo {
            margin-bottom: 10px;
            max-width: 120px;
            width: 100%;
            height: auto;
        }

        h2 {
            color: #4a90e2;
            font-size: 28px;
            text-align: center;
            margin: 0;
        }

        /* Pesan */
        .message {
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            text-align: center;
        }

        .success { 
            color: #3c763d; 
            background-color: #dff0d8; 
        }

        .error { 
            color: #a94442; 
            background-color: #f2dede; 
        }

        /* Form */
        form {
            width: 100%;
        }

        .form-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            width: 100%;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"], input[type="password"], select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        input[type="text"]:focus, input[type="password"]:focus, select:focus {
            border-color: #4a90e2;
            outline: none;
        }

        /* Button */
        .button-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }

        .button {
            background-color: #4a90e2;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            padding: 14px 28px;
            width: 100%;
            max-width: 280px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #357abd;
        }

        /* Navigasi */
        .navigation-links {
            text-align: center;
            background-color: #e0f4e0;
            color: #2e7d32;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }

        .navigation-links a {
            color: #2e7d32;
            text-decoration: none;
        }

        .navigation-links a:hover {
            text-decoration: underline;
        }

        /* Nama Wilayah Terpilih */
        .selected-wilayah {
            font-style: italic;
            color: #555;
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
            width: 100%;
        }

        /* Responsif untuk berbagai ukuran layar */
        @media screen and (max-width: 1200px) {
            .form-group {
                flex: 1 1 45%;
            }
        }

        @media screen and (max-width: 900px) {
            .form-group {
                flex: 1 1 45%;
            }
        }

        @media screen and (max-width: 768px) {
            .form-group {
                flex: 1 1 100%;
            }
            .register-container {
                padding: 25px 20px;
            }
            .logo {
                max-width: 100px;
            }
            h2 {
                font-size: 24px;
            }
            .button {
                font-size: 16px;
                padding: 12px 24px;
            }
        }

        @media screen and (max-width: 480px) {
            .form-content {
                gap: 15px;
            }
            .register-container {
                padding: 20px 15px;
            }
            .logo {
                max-width: 80px;
            }
            h2 {
                font-size: 22px;
            }
            .button {
                font-size: 14px;
                padding: 10px 20px;
            }
            .navigation-links {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
    <script>
        function tampilkanNamaWilayah() {
            var select = document.getElementById("id_wilayah");
            var selectedOption = select.options[select.selectedIndex];
            var wilayahTerpilih = document.getElementById("wilayah_terpilih");
            wilayahTerpilih.textContent = selectedOption.text;
        }
    </script>
</head>
<body>
    <div class="register-container">
        <img src="../assets/img/sinderela.png" alt="SINDERELA Logo" class="logo"> <!-- Adjust path as necessary -->
        <h2>Register</h2>

        <!-- Display success or error message -->
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-content">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="nomor_hp">Nomor HP</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" required>
                </div>
                <div class="form-group">
                    <label for="id_wilayah">Wilayah</label>
                    <select id="id_wilayah" name="id_wilayah" onchange="tampilkanNamaWilayah()" required>
                        <option value="">Pilih Wilayah</option>
                        <?php foreach ($wilayahList as $wilayah): ?>
                            <option value="<?= htmlspecialchars($wilayah['id']); ?>"><?= htmlspecialchars($wilayah['nama_wilayah']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" required>
                        <option value="klien">Klien</option>
                        <!-- Tambahkan opsi role lainnya jika diperlukan -->
                    </select>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="button">Register</button>
            </div>
        </form>

        <div class="navigation-links">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
