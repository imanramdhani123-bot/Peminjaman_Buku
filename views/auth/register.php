<?php include 'views/layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Pustaka Warna Induk</h2>
        <h3>Daftar Akun Baru</h3>
        <form action="index.php?page=register" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Kata Sandi</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn-primary">Daftar</button>
        </form>
        <p><a href="index.php?page=login">Sudah punya akun? Masuk</a></p>
    </div>
</div>