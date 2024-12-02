<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO pengguna (username, email, password_hash) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Registrasi berhasil. <a href='login_user.php'>Login di sini</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2; /* Latar belakang abu-abu terang */
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2 {
            margin-bottom: 30px;
            color: #4e5d6c; /* Warna biru pastel yang lembut */
            font-weight: 600;
        }
        .btn-primary {
            background-color: #4e5d6c;
            border-color: #4e5d6c;
        }
        .btn-primary:hover {
            background-color: #3b4753;
            border-color: #2f3a44;
        }
        .form-label {
            font-weight: 600;
            color: #4e5d6c;
        }
        footer {
            text-align: center;
            margin-top: 30px;
        }
        footer a {
            color: #007bff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Registrasi Pengguna Baru</h2>
    
    <!-- Form Registrasi -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <!-- Footer -->
    <footer>
        <a href="privacy_policy.php">Privacy Policy</a> | 
        <a href="terms_of_service.php">Terms of Service</a>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
