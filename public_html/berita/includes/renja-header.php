<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renja - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        /* Header Styles */
        header {
            background-color: #007bff;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            text-decoration: none;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #28a745;
        }

        .btn-back:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Main Content Area */
        .content {
            padding: 30px;
            background-color: #fff;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .content h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .content p {
            font-size: 16px;
            color: #555;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            header h1 {
                font-size: 22px;
            }

            .header-buttons {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Renja - Admin Panel</h1>
        <div class="header-buttons">
            <a href="admin_dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
            <a href="program-edit.php" class="btn btn-back">Program</a>
            <a href="tambah_berita.php" class="btn btn-secondary">Berita</a>
            <a href="kelola_galeri.php" class="btn btn-back">Galeri</a>
        </div>
    </header>

    

</body>
</html>
