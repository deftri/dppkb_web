<div class="col-md-3">
    <h4 class="text-center mb-3" style="font-family: 'Arial', sans-serif; color: #333;">Semua Kategori</h4>
    <div class="row">
        <?php
        // Query untuk menampilkan kategori
        $kategori_sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
        $kategori_result = $conn->query($kategori_sql);

        if ($kategori_result && $kategori_result->num_rows > 0) {
            $kategori_per_row = 3; // Jumlah kategori per baris
            $current_col = 0;
            while ($row_kategori = $kategori_result->fetch_assoc()) {
                echo "<div class='col-12 col-sm-6 col-md-4 mb-3'>"; // Responsif: 12 untuk mobile, 6 untuk tablet, 4 untuk desktop
                echo "<a href='index.php?kategori=" . urlencode($row_kategori['nama_kategori']) . "' class='btn btn-outline-primary btn-block kategori-btn'>" . htmlspecialchars($row_kategori['nama_kategori']) . "</a>";
                echo "</div>";
                $current_col++;
            }
        } else {
            echo "<p class='text-muted'>Tidak ada kategori.</p>";
        }
        ?>
    </div>
</div>

<!-- Tambahkan styling CSS -->
<style>
    .kategori-btn {
        font-size: 1rem;
        font-weight: 600;
        text-transform: capitalize;
        padding: 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-align: center;
    }

    .kategori-btn:hover {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }

    .kategori-btn:focus {
        outline: none;
    }

    .row {
        margin-right: 0;
        margin-left: 0;
    }

    h4 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
    }

    @media (max-width: 768px) {
        .col-md-3 {
            margin-top: 30px;
        }
    }
</style>
