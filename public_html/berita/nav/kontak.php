<?php
$halaman = 'kontak.php';
include '../includes/header.php';
?>

<!-- Konten Kontak -->
<h2>Kontak Kami</h2>
<form>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" placeholder="Masukkan nama Anda">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda">
    </div>
    <div class="mb-3">
        <label for="pesan" class="form-label">Pesan</label>
        <textarea class="form-control" id="pesan" rows="4" placeholder="Tulis pesan Anda"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Kirim</button>
</form>

<?php
include '../includes/footer.php';
?>
