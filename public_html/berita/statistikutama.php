<section class="kontak-statistik mb-5" id="kontak" style="background-color: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <h2 class="text-center mb-4">Statistik Pengunjung & Jajak Pendapat</h2>
        <div class="row justify-content-center">
            <!-- Kolom Statistik Pengunjung -->
            <div class="col-lg-6 col-md-8 col-12 mb-4">
                <h4>Statistik Pengunjung</h4>
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Kunjungan</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['total_hit']); ?></h5>
                    </div>
                </div>
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Kunjungan Hari Ini</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['hari_ini']); ?></h5>
                    </div>
                </div>
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Kunjungan Kemarin</div>
                    <div class="card-body text-center">
                        <h5><?php echo number_format($statistik['kemarin']); ?></h5>
                    </div>
                </div>
            </div>

            <!-- Kolom Jajak Pendapat -->
            <div class="col-lg-6 col-md-8 col-12">
                <div class="poll-container">
                    <h4>Jajak Pendapat</h4>
                    <form method="POST" action="index.php#kontak">
                        <div class="form-group">
                            <p>Menurut Anda, apakah isi website ini bermanfaat?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="ya" value="Ya" required>
                                <label class="form-check-label" for="ya">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="cukup" value="Cukup">
                                <label class="form-check-label" for="cukup">Cukup</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="tidak" value="Tidak">
                                <label class="form-check-label" for="tidak">Tidak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vote" id="tidak_tahu" value="Tidak Tahu">
                                <label class="form-check-label" for="tidak_tahu">Tidak Tahu</label>
                            </div>
                        </div>
                        <button type="submit" name="poll" class="btn btn-primary w-100 mt-3">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Styling untuk statistik pengunjung */
        .card-body h5 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* Styling untuk form jajak pendapat */
        .poll-container form .form-check-label {
            font-weight: 500;
        }

        /* Styling untuk form jajak pendapat agar lebih responsif */
        .poll-container button {
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .poll-container button:hover {
            background-color: #0056b3;
        }

        /* Pastikan form jajak pendapat responsif */
        .poll-container .form-check {
            margin-bottom: 1rem;
        }

        .poll-container form {
            margin-top: 20px;
        }
    </style>
</section>
