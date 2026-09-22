<?php include 'views/layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Pustaka Warna Induk</h2>
        <h3>Masuk</h3>
        <form action="index.php?page=login" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-primary">Masuk</button>
        </form>
        <p><a href="index.php?page=register">Belum punya akun? Daftar</a></p>
    </div>
</div>