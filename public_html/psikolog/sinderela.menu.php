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
        <h1>Selamat Datang di <span>Sinderela</span></h1>
        <p>Silakan pilih menu di bawah untuk memulai konseling Anda.</p>

        <!-- Buttons -->
        <a href="../public/login.php" class="btn btn-custom btn-start-chat">
            <i class="fas fa-comments"></i> Mulai Chat
        </a>
        <a href="../public/register.php" class="btn btn-custom btn-register">
            <i class="fas fa-user-plus"></i> Register
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
