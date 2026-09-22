<?php include 'views/layout/header.php'; ?>
<div class="layout">
    <?php include 'views/layout/sidebar.php'; ?>
    <div class="main-content">
        <h2>Detail Transaksi Peminjaman</h2>
        <br>
        <div style="background: white; padding: 25px; border-radius: 8px; width: 420px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <p style="margin-bottom: 10px;"><strong>ID Pinjam:</strong> #<?= $detail['id_pinjam'] ?></p>
            <p style="margin-bottom: 10px;"><strong>Judul Buku:</strong> <?= htmlspecialchars($detail['judul_buku']) ?></p>
            <p style="margin-bottom: 10px;"><strong>Peminjam:</strong> <?= htmlspecialchars($detail['nama_lengkap']) ?> (<?= htmlspecialchars($detail['email']) ?>)</p>
            <p style="margin-bottom: 10px;"><strong>Tgl Pinjam:</strong> <?= $detail['tgl_pinjam'] ?></p>
            <p style="margin-bottom: 10px;"><strong>Tgl Jatuh Tempo:</strong> <?= $detail['tgl_jatuh_tempo'] ?></p>
            <p style="margin-bottom: 10px;"><strong>Status:</strong> <?= $detail['status'] ?></p>
            <p style="margin-bottom: 20px;"><strong>Denda Saat Ini:</strong> Rp <?= number_format($detail['denda'] ?? 0) ?></p>

            <?php if ($detail['status'] !== 'Kembali'): ?>
                <a href="index.php?page=detail-peminjaman&id=<?= $detail['id_pinjam'] ?>&action=kembali" 
                   class="btn-primary" 
                   style="display:block; text-align:center; text-decoration:none;"
                   onclick="return confirm('Kembalikan buku ini sekarang?')">
                    Kembalikan Buku
                </a>
            <?php else: ?>
                <button disabled style="width:100%; padding: 10px; background:#10b981; color:white; border:none; border-radius:5px; font-weight:bold;">Buku Sudah Dikembalikan</button>
            <?php endif; ?>
        </div>
    </div>
</div>