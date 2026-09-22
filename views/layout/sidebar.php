<div class="sidebar">
    <div class="sidebar-title">
        Pustaka Warna Induk
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php?page=buku" class="<?= ($_GET['page'] ?? '') === 'buku' ? 'active' : '' ?>">
                📊 Home / Katalog Buku
            </a>
        </li>
        <li>
            <a href="index.php?page=peminjaman" class="<?= ($_GET['page'] ?? '') === 'peminjaman' ? 'active' : '' ?>">
                📚 Peminjaman Buku
            </a>
        </li>
        <li>
            <a href="index.php?page=logout" style="color: #ef4444;">
                🚪 Keluar
            </a>
        </li>
    </ul>
</div>