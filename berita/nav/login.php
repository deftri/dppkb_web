<?php
$halaman = 'login.php';
include '../includes/header.php';
?>

<!-- Konten Login -->
<h2>Login</h2>
<form method="POST" action="proses_login.php">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username Anda" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php
include '../includes/footer.php';
?>
