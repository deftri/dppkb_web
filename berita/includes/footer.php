<?php
// includes/footer.php
include '../config/config.php'; // Menyambungkan ke database

// Ambil data footer
$sql = "SELECT * FROM footer WHERE id = 1";
$result = $conn->query($sql);
$footer = $result->fetch_assoc();
?>

<!-- Footer -->
<footer class="bg-dark text-white pt-4 pb-3">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4 col-lg-4 mb-3">
                <h5 class="text-uppercase font-weight-bold text-warning">Tentang Kami</h5>
                <p>
                    Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim bertanggung jawab untuk mengelola dan mengawasi program pengendalian penduduk serta keluarga berencana di wilayah ini. Kami berkomitmen untuk memberikan informasi yang akurat dan terpercaya kepada masyarakat.
                </p>
            </div>

            <!-- Contact Section -->
            <div class="col-md-4 col-lg-4 mb-3">
                <h5 class="text-uppercase font-weight-bold text-warning">Kontak</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-home mr-2"></i> <?php echo $footer['alamat']; ?></li>
                    <li><i class="fas fa-envelope mr-2"></i> <a href="mailto:<?php echo $footer['email']; ?>" class="text-white"><?php echo $footer['email']; ?></a></li>
                    <li><i class="fas fa-phone mr-2"></i> <?php echo $footer['telepon']; ?></li>
                    <li><i class="fas fa-fax mr-2"></i> <?php echo $footer['fax']; ?></li>
                </ul>
            </div>

            <!-- Quick Links Section -->
            <div class="col-md-4 col-lg-4 mb-3">
                <h5 class="text-uppercase font-weight-bold text-warning">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Beranda</a></li>
                    <li><a href="tentang.php" class="text-white">Tentang Kami</a></li>
                    <li><a href="galeri.php" class="text-white">Galeri</a></li>
                    <li><a href="kumpulan_berita.php" class="text-white">Berita</a></li>
                    <li><a href="kontak.php" class="text-white">Kontak</a></li>
                </ul>
            </div>
        </div>

        <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">
                    &copy; <?php echo date("Y"); ?> 
                    <strong>Dinas Pengendalian Penduduk dan Keluarga Berencana</strong> 
                    Kabupaten Muara Enim. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="privacy_policy.php" class="text-white mr-3">Privacy Policy</a>
                <a href="terms_of_service.php" class="text-white">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
<!-- Akhir Footer -->

<!-- JS Bootstrap dan dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/js/all.min.js"></script>
<!-- Custom JS -->
<script src="js/index.js"></script>
</body>
</html>
